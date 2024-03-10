<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Exports\EnrolmentsExport;
use App\Exports\PorductOrderExport;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Language;
use App\Models\PaymentMethods;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EnrolmentController extends Controller
{
    public function index(Request $request)
    {

        $orderId = $paymentStatus = null;

        if ($request->filled('order_id')) {
            $orderId = $request['order_id'];
        }

        if ($request->filled('status')) {
            $paymentStatus = $request['status'];
        }

        $pricingType = 'all';
        if ($request->filled('type')) {
            $pricingType = $request['type'];
        }

        $enrolments = CourseEnrolment::when($orderId, function ($query, $orderId) {
            return $query->where('order_id', 'like', '%' . $orderId . '%');
        })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);

            })->when($pricingType, function ($query, $pricingType) {
                if ($pricingType == 'free') {
                    return $query->whereNull('payment_method');
                } elseif ($pricingType == 'premium') {
                    return $query->whereNotNull("payment_method");
                } else {
                    return $query;
                }
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('backend.curriculum.enrolment.index', compact('enrolments'));
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $enrolment = CourseEnrolment::find($id);

        if ($request['payment_status'] == 'completed') {
            $enrolment->update([
                'payment_status' => 'completed'
            ]);

            $invoice = $this->generateInvoice($enrolment);

            $enrolment->update([
                'invoice' => $invoice
            ]);

            $this->sendMail($request, $enrolment, 'enrolment approved');
        } else if ($request['payment_status'] == 'pending') {
            $enrolment->update([
                'payment_status' => 'pending'
            ]);
        } else {
            $enrolment->update([
                'payment_status' => 'rejected'
            ]);

            $this->sendMail($request, $enrolment, 'enrolment rejected');
        }

        return redirect()->back();
    }

    public function generateInvoice($enrolmentInfo)
    {
        $fileName = $enrolmentInfo->order_id . '.pdf';
        $directory = './file/invoices/';

        @mkdir($directory, 0775, true);

        $fileLocated = $directory . $fileName;

        // get course title
        $language = $this->getLanguage();

        $course = $enrolmentInfo->course()->first();
        $courseInfo = $course->information()->where('language_id', $language->id)->first();

        $width = "50%";
        $float = "right";
        $mb = "35px";
        $ml = "18px";

        PDF::loadView('frontend.curriculum.invoice', compact('enrolmentInfo', 'courseInfo', 'width', 'float', 'mb', 'ml'))->save($fileLocated);

        return $fileName;
    }

    public function sendMail($request, $enrolmentInfo, $mailFor)
    {
        // first get the mail template info from db
        if ($mailFor == 'enrolment approved') {
            $mailTemplate = MailTemplate::where('mail_type', 'course_enrolment_approved')->first();
        } else {
            $mailTemplate = MailTemplate::where('mail_type', 'course_enrolment_rejected')->first();
        }

        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second get the website title & mail's smtp info from db
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $customerName = $enrolmentInfo->billing_first_name . ' ' . $enrolmentInfo->billing_last_name;
        $orderId = $enrolmentInfo->order_id;

        $language = $this->getLanguage();
        $course = Course::where('id', $enrolmentInfo->course_id)->firstOrFail();
        $courseInfo = $course->information()->where('language_id', $language->id)->firstOrFail();
        $courseTitle = $courseInfo->title;

        $websiteTitle = $info->website_title;

        $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
        $mailBody = str_replace('{order_id}', $orderId, $mailBody);
        $mailBody = str_replace('{title}', '<a href="' . route('course_details', $courseInfo->slug) . '">' . $courseTitle . '</a>', $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

        // initialize a new mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            $mail->isSMTP();
            $mail->Host = $info->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $info->smtp_username;
            $mail->Password = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = $info->smtp_port;
        }

        // finally add other informations and send the mail
        try {
            // Recipients
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($enrolmentInfo->billing_email);

            // Attachments (Invoice)
            if (!is_null($enrolmentInfo->invoice)) {
                $mail->addAttachment('file/invoices/' . $enrolmentInfo->invoice);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();

            $request->session()->flash('success', 'Payment status updated & mail has been sent successfully!');
        } catch (Exception $e) {
            $request->session()->flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }

        return;
    }

    public function show($id)
    {
        $enrolmentInfo = CourseEnrolment::find($id);

        // get course title
        $language = $this->getLanguage();

        $course = $enrolmentInfo->course()->first();
        $courseInfo = $course->information()->where('language_id', $language->id)->first();
        $courseTitle = $courseInfo->title;

        return view('backend.curriculum.enrolment.details', compact('enrolmentInfo', 'courseTitle'));
    }

    public function destroy($id)
    {
        $enrolmentInfo = CourseEnrolment::find($id);

        // first, delete the attachment
        @unlink('file/attachments/' . $enrolmentInfo->attachment);

        // second, delete the invoice
        @unlink('file/invoices/' . $enrolmentInfo->invoice);

        $enrolmentInfo->delete();

        return redirect()->back()->with('success', 'Enrolment deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $enrolmentInfo = CourseEnrolment::find($id);

            // first, delete the attachment
            @unlink('file/attachments/' . $enrolmentInfo->attachment);

            // second, delete the invoice
            @unlink('file/invoices/' . $enrolmentInfo->invoice);

            $enrolmentInfo->delete();
        }

        $request->session()->flash('success', 'Enrolments deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function report(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $paymentMethod = $request->payment_method;
        $deLang = Language::where('is_default', 1)->first();

        if (!empty($fromDate) && !empty($toDate)) {
            $enrolments = CourseEnrolment::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);
            })
                ->whereNot("payment_method", null)->orderByDesc('id');

            Session::put('enrollment_report', $enrolments->get());
            $data['enrolments'] = $enrolments->paginate(10);
        } else {
            Session::put('enrollment_report', []);
            $data['enrolments'] = [];
        }

        $data['onPms'] = PaymentMethods::where('status', 1)->get();
        $data['deLang'] = $deLang;
        $data['abs'] = Basic::select('base_currency_symbol_position', 'base_currency_symbol')->first();


        return view('backend.curriculum.enrolment.report', $data);
    }

    public function export()
    {
        $enrolments = Session::get('enrollment_report');
        if (empty($enrolments) || count($enrolments) == 0) {
            Session::flash('warning', 'There is no enrolment to export');
            return back();
        }
        return Excel::download(new EnrolmentsExport($enrolments), 'enrolments.csv');
    }
}
