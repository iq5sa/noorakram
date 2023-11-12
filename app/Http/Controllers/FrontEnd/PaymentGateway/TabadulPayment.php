<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\CourseEnrolment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TabadulPayment extends Controller
{


    private $username;
    private $password;

    public function __construct()
    {
        $this->username = "Noor_Akram_api";
        $this->password = '6iA`[6"84Pa5';
    }

    public function pay($amount, $orderId, $orderType)
    {
        $orderData = [
            "returnUrl" => route("credit.payment.redirect"),
            "amount" => $amount,
            "orderType" => $orderType,
            "orderId" => $orderId
        ];
        $request = $this->sendRequest($orderData);
        $lastEnrolment = session()->get('enrolment', 1);

        $data = json_decode($request);

        $enrolId = $lastEnrolment->id;
        $enrolment = CourseEnrolment::find($enrolId);

        if (isset($data->formUrl)) {
            $enrolment->tabadul_order_id = $data->orderId;
            $enrolment->save();
            return redirect($data->formUrl);
        }

        if (isset($data->errorCode)) {
            if ($data->errorCode == 1) {

                $enrolment->order_id = Controller::generateOrderID(Auth::id());
                $enrolment->payment_method = "credit";
                $enrolment->save();

                request()->session()->put('enrolment', $enrolment);
                return redirect()->route("payment.checkout", ["order" => $enrolment->order_id, "orderType" => $orderType]);
            }
        }


        //return session("enrolment");
        return $request;
    }

    public function sendRequest($orderData)
    {

        $url = "https://epg.tabadul.iq/epg/rest/register.do";
        $headers = [
            'Authorization' => '39cd434c94fa49e99646b58f31bbdb88',
            "content-type" => "application/json"
        ];

        $body = [
            "userName" => $this->username,
            "password" => $this->password,
            "orderNumber" => $orderData["orderId"],
            "amount" => $orderData["amount"] . '000',
            "currancy" => "368",
            "returnUrl" => $orderData["returnUrl"],
            "clientId" => \Illuminate\Support\Facades\Auth::id(),
            "description" => $orderData["orderType"],
            "language" => "AR"
        ];


        return $this->build($url, $headers, $body);


    }

    public function build($url, $headers = [], $body = [])
    {

        return Http::withHeaders($headers)->asForm()->post($url, $body);
    }

    public function getOrderStatus($order_id)
    {
        $url = "https://epgtest.tabadul.iq:9444/epg/rest/getOrderStatus.do";

        return Http::asForm()->post($url, [
            "userName" => $this->username,
            "password" => $this->password,
            "orderId" => $order_id
        ]);

    }


    public function redirect()
    {
        return redirect()->route("courses");
    }

}
