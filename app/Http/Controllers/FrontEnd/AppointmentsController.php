<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Hawkiq\LaravelZaincash\Services\ZainCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AppointmentsController extends Controller
{
    //

    public function index()
    {
        $language = $this->getLanguage();


        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

        $queryResult['pageHeading'] = "حجز موعد";

        $queryResult['breadcrumbImg'] = "appointment.png";


        return view('frontend.appointments.index', $queryResult);
    }


    public function payment(Request $request)
    {


        $totalPrice = $request->totalPrice;
        $info = DB::table('basic_settings')
            ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $subject = $request->subject;
        $message = $request->subject . "<br>" . $request->phoneNumber . "<br>" . $request->name . "<br>" . $request->email .
            "<br>" . $request->date . " " . $request->time . "<br>" . $request->hoursCount . "<br>" . $request->totalPriceLabel;

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
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress("info@noorakram.com");

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            $request->session()->flash('warning', 'لم يتم ارسال البريد الالكتروني');

            return redirect()->back();
        }


        if ($request->paymentMethod == "cash") {

            //https://wa.me/+9647726908090
            return redirect()->to("https://wa.me/+9647726908090");
        } elseif ($request->paymentMethod == "zainCash") {

            $zaincash = new ZainCash();
            //The total price of your order in Iraqi Dinar only like 1000 (if in dollar, multiply it by dollar-dinar exchange rate, like 1*1500=1500)
            //Please note that it MUST BE MORE THAN 1000 IQD
            $amount = $totalPrice;

            //Type of service you provide, like 'Books', 'ecommerce cart', 'Hosting services', ...
            $service_type = "appointment";

            //Order id, you can use it to help you in tagging transactions with your website IDs, if you have no order numbers in your website, leave it 1
            $order_id = time();

            $payload = $zaincash->request($amount, $service_type, $order_id);

            return $payload;

        } elseif ($request->paymentMethod == "master") {
            $http = Http::withHeaders([
                "Authorization" => "39cd434c94fa49e99646b58f31bbdb88"
            ])
                ->post("https://api.uat.pay.qi.iq/api/v0/transactions/business/token", [
                    "order" => [
                        "amount" => $request->totalPrice,
                        "currency" => "IQD",
                        "orderId" => "" . time() . ""
                    ],
                    "timestamp" => date("Y-m-d H:i:s"),
                    "successUrl" => env("APP_URL") . "/appointment/book/success",
                    "failureUrl" => env("APP_URL") . "/appointment/book/cancel",
                    "cancelUrl" => "https://google.com" . "/appointment/book/cancel"
                ]);

            if ($http["success"]) {
                return redirect()->to($http["data"]["link"]);
            }

        }


    }


    public function paymentSuccess()
    {
        $language = $this->getLanguage();


        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

        $queryResult['pageHeading'] = "contact";
        $queryResult['paidVia'] = "Master";

        $queryResult['bgImg'] = $this->getBreadcrumb();
        return view("frontend.payment.success", $queryResult);
    }


}
