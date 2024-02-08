<?php

namespace App\Http\Controllers\FrontEnd\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\PaymentGateway\CashController;
use App\Http\Controllers\FrontEnd\PaymentGateway\TabadulPayment;
use App\Models\Curriculum\CourseEnrolment;
use Exception;
use Hawkiq\LaravelZaincash\Services\ZainCash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AjaxController extends Controller
{

    public function sendResponse($data, $message, $status = 200): Response|Application|ResponseFactory
    {
        return response(
            [
                "data" => $data,
                "message" => $message,
            ],
            $status
        );
    }

    public function checkout(Request $request): Response|Application|ResponseFactory
    {


        $validator = $this->validateInputs($request);
        try {
            $validate = $validator->validate();
        } catch (ValidationException $e) {
            return $this->sendResponse($e->errors(), "", 401);
        }

        if ($validator->fails()) {
            return $this->sendResponse($validate, "", 401);
        }

        $orderId = $request->get("order_id");
        $orderType = $request->get("order_type");
        $enrolment = $this->getEnrolment($orderId);
        $enrolmentData = $enrolment->get()->first();
        try {
            $discount = session()->get('discountedPrice');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            Log::error($e->getMessage());
            $discount = null;
        }
        $course_price = $enrolmentData->course_price;


        if ($discount) {
            $course_price = $discount;
            $enrolment->update(['discount' => $discount, 'grand_total' => $discount]);
        }

        if ($enrolmentData->grand_total != $course_price) {
            $enrolment->update(['grand_total' => $course_price]);
        }


        switch ($validate["payment_methods"]) {
            case "cash":
                if (empty($request->get('cash_code'))) {
                    return $this->sendResponse([], 'الرجاء أضافه كود الشراء', 401);
                }

                $CashPayment = new CashController();
                $pay = $CashPayment->pay($request->get('cash_code'), $orderId);
                if (is_null($pay['error'])) {
                    return $this->sendResponse(["url" => $pay["url"]], "تمت العملية بنجاح");
                } else {
                    return $this->sendResponse([], $pay['error'], 401);
                }


            case "zain_cash":

                $zainCash = new ZainCash();
                try {
                    $payload = $zainCash->request($course_price, $orderType, $orderId);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                    return $this->sendResponse([], $e->getMessage(), 401);
                }
                return $this->sendResponse(["url" => $payload->getTargetUrl()], "");
            case "credit":

                $enrolment->update(['payment_method' => 'credit']);
                $credit = new TabadulPayment();
                $amount = intval($course_price);
                $orderData = [
                    "amount" => $amount,
                    "order_id" => $orderId,
                    "client_id" => Auth::id(),
                    "desc" => $orderType
                ];
                $response = $credit->createOrder($orderData);


                if ($response["errorCode"] == 0) {
                    $enrolment->update(['tabadul_order_id' => $response['orderId'], 'attempts' => $enrolmentData->attempts + 1]);
                    return $this->sendResponse(["url" => $response['formUrl']], "");
                } elseif ($response["errorCode"] == 1) {
                    // Generate a new order id
                    $newOrderId = self::generateOrderID(Auth::id());
                    $enrolment->update(['order_id' => $newOrderId, 'attempts' => $enrolmentData->attempts + 1]);

                    // Update the request object with the new order id
                    $request->merge(['order_id' => $newOrderId]);

                    return $this->checkout($request);

                } else {
                    return $this->sendResponse([], $response['errorMessage'], 401);
                }


        }


        return $this->sendResponse($validate, "All right");
    }


    private function validateInputs(Request $request): \Illuminate\Validation\Validator
    {

        $rules = [
            "payment_methods" => ["required", Rule::exists('payment_methods', 'keyword')],
            "order_type" => ["required", Rule::in(['course', "appointment"])],
            "order_id" => ["required", Rule::exists('course_enrolments', 'order_id')]
        ];

        $messages = [
            "payment_methods.required" => 'الرجاء اختيار وسيلة دفع',
            "payment_methods.exists" => 'الرجاء اختيار وسيلة دفع صحيحة',
            'order_type.required' => 'الرجاء اختيار نوع الطلب',
            'order_type.in' => 'الرجاء اختيار نوع الطلب صحيح',
            'order_id.required' => ' رقم الطلب غير موجود حاول مرة اخرى',
        ];

        return Validator::make(
            $request->only(['order_type', 'order_id', 'payment_methods']),
            $rules,
            $messages
        );
    }


    public function getEnrolment($orderId)
    {

        return CourseEnrolment::where("order_id", $orderId);

    }


}