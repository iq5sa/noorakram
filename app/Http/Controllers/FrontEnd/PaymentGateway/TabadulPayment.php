<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TabadulPayment extends Controller
{


    private static string $apiHost = "https://epg.tabadul.iq/epg/rest";
    private string $createOrderEndPoint;

    public function __construct()
    {
        $this->createOrderEndPoint = $this::$apiHost . "/register.do";
//        $this->getOrderStatusEndPoint = $this::$apiHost . "/getOrderStatus.do";
    }

    public function createOrder($orderData)
    {

        $headers = [
            "Content-Type" => "application/x-www-form-urlencoded"
        ];

        $body = [
            "userName" => Config::get('tabadul.username'),
            "password" => Config::get('tabadul.password'),
            "returnUrl" => Config::get('tabadul.returnUrl'),
            "currancy" => Config::get('tabadul.currency'),
            "language" => Config::get('tabadul.language'),
            "orderNumber" => $orderData["order_id"],
            "clientId" => $orderData["client_id"],
            "amount" => $orderData["amount"] . '000',
            "description" => $orderData["desc"],
        ];


        return $this->buildHttpRequest($this->createOrderEndPoint, $headers, $body);


    }

    public function buildHttpRequest($url, $headers = [], $body = [])
    {

        try {
            $response = Http::timeout(30)->withHeaders($headers)->asForm()->post($url, $body);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            $response = Http::retry(3, 100)->withHeaders($headers)->asForm()->post($url, $body);

            Log::error('Payment request failed: ' . $e->getMessage());

            return $response;
        }
    }

    public function getOrderStatus($order_id)
    {
        $url = "https://epg.tabadul.iq/epg/rest/getOrderStatus.do";

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
