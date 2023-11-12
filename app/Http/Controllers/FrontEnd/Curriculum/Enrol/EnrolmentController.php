<?php

namespace App\Http\Controllers\FrontEnd\Curriculum\Enrol;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EnrolmentController extends Controller
{


    public function enrol(Request $request, $id)
    {

        $beforeEnrol = $this->beforeEnrol($request, $id);

        if (!$beforeEnrol["status"]) {
            $request->session()->flash("error", $beforeEnrol["message"]);
            return redirect($beforeEnrol["redirect"]);
        }

        $course = Course::where("id", "=", $id)->get();

        $isUserEnrolled = $this->isUserEnrolled($id, Auth::id());

        if ($isUserEnrolled) {
            if ($isUserEnrolled->payment_status == "pending") {
                return redirect()->route("payment.checkout", ["orderType" => "course", "order_id" => $isUserEnrolled->order_id]);
            }

            if ($isUserEnrolled->payment_status == "completed") {
                $request->session()->flash('warning', 'انت مشترك فعلا في الدورة');
                return redirect()->back();
            }


        }


        // New Free Enrol
        if ($course->first()->pricing_type == "free") {
            $freeCourseEnrol = new FreeCourseEnrolController();
            return $freeCourseEnrol->process($id);
        }

        // New Premium Enrol
        if ($course->first()->pricing_type == "premium") {
            $premiumCourseEnrol = new PremiumCourseEnrolController();
            return $premiumCourseEnrol->process($id);
        }

        return redirect()->back();
    }

    private function beforeEnrol(Request $request, $course_id)
    {

        $authCheck = Auth::guard('web')->check();
        $course_id = intval($course_id);
        $data["status"] = true;
        if (!$authCheck) {
            $data["status"] = false;
            $data["message"] = "يجب تسجيل الدخول أولا";
            $data["redirect"] = route('user.login', ['redirectPath' => 'course_details']);
            return $data;
        }

        if ($this->getUser()->edit_profile_status == 0) {
            $data["status"] = false;
            $data["message"] = 'يجب إكمال الملف الشخصي لإكمال عملية الشراء';
            $data["redirect"] = route('user.edit_profile', ['redirectPath' => 'course_details']);
            return $data;

        }

        $course = Course::where("id", "=", $course_id)->get();
        if ($course->count() == 0) {
            $data["status"] = false;
            abort(404);
        }

        return $data;

    }

    public function getUser(): Authenticatable
    {
        return Auth::guard('web')->user();
    }

    private function isUserEnrolled($course_id, $user_id)
    {
        $course_id = intval($course_id);
        $enrollment = CourseEnrolment::where("course_id", "=", $course_id)->where("user_id", "=", $user_id)->get();

        return $enrollment->count() >= 1 ? $enrollment->first() : false;
    }

    public function calculation(Request $request, $course_id): array
    {
        $course = Course::where('id', '=', $course_id)->where('status', '=', 'published')->firstOrFail();

        $course_price = floatval($course->current_price);

        if ($request->session()->has('discountedCourse')) {
            $_course_id = $request->session()->get('discountedCourse');

            if ($course_id == $_course_id) {
                if ($request->session()->has('discount')) {
                    $_discount = $request->session()->get('discount');
                }

                if ($request->session()->has('discountedPrice')) {
                    $_course_new_price = $request->session()->get('discountedPrice');
                }
            }
        }

        $calculatedData = array(
            'coursePrice' => $course_price,
            'discount' => isset($_discount) ? floatval($_discount) : null,
            'grandTotal' => isset($_course_new_price) ? floatval($_course_new_price) : $course_price
        );

        return $calculatedData;
    }

    public function storeData($info)
    {

        $user = Auth::guard('web')->user();
        return CourseEnrolment::create([
            'user_id' => $user->id,
            'order_id' => array_key_exists('order_id', $info) ? $info['order_id'] : $this->generateOrderID($user->id),
            'billing_first_name' => $user->first_name,
            'billing_last_name' => $user->last_name,
            'billing_email' => $user->email,
            'billing_contact_number' => $user->contact_number,
            'billing_address' => $user->address,
            'billing_city' => $user->city,
            'billing_state' => $user->state,
            'billing_country' => $user->country,
            'course_id' => $info['course_id'],
            'course_price' => array_key_exists('coursePrice', $info) ? $info['coursePrice'] : null,
            'discount' => array_key_exists('discount', $info) ? $info['discount'] : null,
            'grand_total' => array_key_exists('grandTotal', $info) ? $info['grandTotal'] : null,
            'currency_text' => array_key_exists('currencyText', $info) ? $info['currencyText'] : "IQD",
            'currency_text_position' => array_key_exists('currencyTextPosition', $info) ? $info['currencyTextPosition'] : "right",
            'currency_symbol' => array_key_exists('currencySymbol', $info) ? $info['currencySymbol'] : "د.ع",
            'currency_symbol_position' => array_key_exists('currencySymbolPosition', $info) ? $info['currencySymbolPosition'] : "left",
            'payment_method' => array_key_exists('paymentMethod', $info) ? $info['paymentMethod'] : null,
            'payment_status' => array_key_exists('paymentStatus', $info) ? $info['paymentStatus'] : null,
            'attachment' => array_key_exists('attachmentFile', $info) ? $info['attachmentFile'] : null,
            'transactionId' => array_key_exists('transactionId', $info) ? $info['transactionId'] : null,
            'transactionToken' => array_key_exists('transactionToken', $info) ? $info['transactionToken'] : null
        ]);
    }

    public function generateInvoice($enrolmentInfo, $course_id): string
    {
        $fileName = $enrolmentInfo->order_id . '.pdf';
        $directory = './file/invoices/';

        @mkdir($directory, 0775, true);

        $fileLocated = $directory . $fileName;

        // get course title
        $language = $this->getLanguage();

        $course = Course::findOrFail($course_id);

        $courseInfo = $course->information()->where('language_id', $language->id)->firstOrFail();

        $width = "50%";
        $float = "right";
        $mb = "35px";
        $ml = "18px";

        PDF::loadView('frontend.curriculum.invoice', compact('enrolmentInfo', 'courseInfo', 'width', 'float', 'mb', 'ml'))->save($fileLocated);

        return $fileName;
    }

    public function sendMail($enrolmentInfo)
    {
        // first get the mail template info from db
        $mailTemplate = MailTemplate::where('mail_type', 'course_enrolment')->first();
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

        // finally add other information and send the mail
        try {
            // Recipients
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($enrolmentInfo->billing_email);

            // Attachments (Invoice)
            $mail->addAttachment('file/invoices/' . $enrolmentInfo->invoice);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();


        } catch (Exception $e) {
            session()->put('error', 'لم يتم إرسال البريد الإلكتروني لوجود خلل فني: ');
        }
    }

    public function complete($order_id, Request $request)
    {


        $language = $this->getLanguage();
//    $queryResult['bgImg'] = $this->getBreadcrumb();


//     forget all session data before proceed
        $request->session()->forget('discountedCourse');
        $request->session()->forget('discount');
        $request->session()->forget('discountedPrice');

        $enrolment = CourseEnrolment::where("order_id", "=", $order_id)->first();
        if (!$enrolment) {
            abort(404);
        }


        $course = Course::where("id", "=", $enrolment->course_id)->first();
//    $courseInfo = $course->information()->where('language_id', $language->id)->firstOrFail();

        //$tabadul = new TabadulPayment();

        //$response = json_decode($tabadul->getOrderStatus($order->gateway_order_id));

        return view('frontend.payment.success', ["enrolment" => $enrolment, "orderType" => $course]);

    }

    public function cancel($id, Request $request)
    {
        $language = $this->getLanguage();

        $course = Course::findOrFail($id);

        $courseInfo = $course->information()->where('language_id', $language->id)->firstOrFail();

        $request->session()->flash('error', 'عفواً، حدث خطأ ما يرجى المحاولة لحقاً');

        // forget all session data before proceed
        $request->session()->forget('discountedCourse');
        $request->session()->forget('discount');
        $request->session()->forget('discountedPrice');

        return redirect()->route('course_details', ['slug' => $courseInfo->slug]);
    }
}
