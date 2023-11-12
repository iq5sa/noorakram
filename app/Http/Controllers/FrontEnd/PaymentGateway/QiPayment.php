<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class QiPayment extends Controller
{


    public function sendRequest($orderData)
    {

        $headers = [
            'Authorization' => '39cd434c94fa49e99646b58f31bbdb88',
            "content-type" => "application/json"
        ];

        $body = [
            "order" => [
                "amount" => $orderData["amount"],
                "currency" => "IQD",
                "orderId" => $orderData["orderId"]
            ],

            "timestamp" => Carbon::today(),
            "successUrl" => 'https://google.com',
            "failureUrl" => 'https://google.com',
            "cancelUrl" => 'https://google.com',
            "webhookUrl" => 'https://google.com'

        ];


        $url = "https://api.uat.pay.qi.iq/api/v0/transactions/business/token";

        $response = $this->build($url, $headers, $body);

        return $response;


    }

    public function build($url, $headers = [], $body = [])
    {

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response;
    }


}
