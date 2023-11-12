<?php

namespace App\Http\Controllers\FrontEnd\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\PaymentGateway\CashController;
use App\Http\Controllers\FrontEnd\PaymentGateway\CashPaymentController;
use App\Http\Controllers\FrontEnd\PaymentGateway\TabadulPayment;
use App\Http\Controllers\FrontEnd\PaymentGateway\ZainCashController;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseInformation;
use App\Models\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function index(Request $request, $order_id)
    {
        $lang = $this->getLanguage();
        $orderType = $request->get('orderType');
        $validator = $this->validateIndex($orderType, $order_id);


        $queryResult["breadcrumbImg"] = 'courses.png';
        $queryResult["pageHeading"] = 'إتمام عملية الشراء';
        if ($validator->fails()) {

            $request->session()->flash('error', "Invalid Input fields");
            return redirect()->back();
        }
        $queryResult["orderType"] = $validator->getData()["orderType"];

        $queryResult["paymentMethods"] = PaymentMethods::where("status", 1)->get();
        if ($orderType == 'course') {
            $enrolment = session('enrolment');
            $course = Course::where("id", "=", $enrolment->course_id)->get()->first();
            $courseInfo = CourseInformation::where("course_id", "=", $course->id)->where("language_id", "=", $lang->id)->get()->first();
            $queryResult["order"] = [
                "name" => "كورس " . $courseInfo->title,
                "desc" => "كورس " . $courseInfo->title . ' المقدم من أكاديميه نور اكرم',
                'discount' => null,
                "price" => $course->current_price
            ];
        }


        return view("frontend.payment.checkout", $queryResult);

    }

    /**
     *
     */
    private function validateIndex($orderType, $order_id)
    {

        $order_id = trim($order_id);
        $fields = [
            "orderType" => $orderType,
            "order_id" => $order_id
        ];


        $rules = [
            "orderType" => ["required", Rule::in(['course', "appointment"])],
            "order_id" => ["required", Rule::exists('course_enrolments', 'order_id')]
        ];

        return Validator::make(
            $fields,
            $rules,
        );
    }

    /**
     * @throws ValidationException
     */
    public function submit(Request $request, $order_id)
    {
        $orderType = $request->get("orderType");
        $this->validateIndex($orderType, $order_id);


        $validatePaymentMethod = Validator::make(
            $request->only(["payment_method"]), ["payment_method" => "required"]);


        if ($validatePaymentMethod->fails()) {
            $request->session()->flash("error", "الرجاء اختيار وسيلة دفع");
            return redirect()->back();
        }

        if ($request->get("payment_method") == 'cash') {
            if (empty($request->get('cash_code'))) {
                $request->session()->flash("error", "الرجاء أضافه كود الشراء");
                return redirect()->back();
            }

            $CashPayment = new CashController();

            return $CashPayment->pay($request->get('cash_code'), $order_id);

        }

        if ($request->get('payment_method') == "zain_cash") {
            $enrolment = session('enrolment');

            $zainCash = new ZainCashController();

            return $zainCash->pay($enrolment->course_price, $order_id, $orderType);
        }

        if ($request->get('payment_method') == "credit") {
            $enrolment = session('enrolment');

            $credit = new TabadulPayment();
            $amount = intval($enrolment->course_price);

            return $credit->pay($amount, $order_id, $orderType);
        }


        return redirect()->back();

    }


    public function success($order_id)
    {
        $enrolment = CourseEnrolment::where("order_id", "=", $order_id)->get()->first();
        $course = Course::where("id", "=", $enrolment->course_id)->get()->first();
        return \view("frontend.payment.success", ["enrolment" => $enrolment, "course" => $course]);
    }

    public function failed(Request $request)
    {
        return \view("frontend.payment.failed");
    }

}
