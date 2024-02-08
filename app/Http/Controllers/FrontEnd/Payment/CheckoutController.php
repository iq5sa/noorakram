<?php

namespace App\Http\Controllers\FrontEnd\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\PaymentGateway\CashController;
use App\Http\Controllers\FrontEnd\PaymentGateway\CashPaymentController;
use App\Http\Controllers\FrontEnd\PaymentGateway\TabadulPayment;
use App\Http\Controllers\FrontEnd\PaymentGateway\ZainCashController;
use App\Models\Appointment;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseInformation;
use App\Models\PaymentMethods;
use Carbon\Carbon;
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
                "price" => $course->current_price,
                "course_id" => $course->id,
                "orderType" => "course"
            ];
        } elseif ($orderType == 'appointment') {
            $appointment = Appointment::where("order_id", "=", $order_id)->get()->first();
            if (!$appointment) {
                $request->session()->flash('error', "Invalid Input fields");
                return redirect()->back();
            }

            $orderName = "حجز جلسة";
            $orderDesc = ' حجز ' . $appointment->session_type;
            $orderDesc .= " في يوم " . Carbon::parse($appointment->date)->locale("ar_IQ")->isoFormat("dddd Do MMMM YYYY");
            $orderDesc .= " في تمام الساعة " . Carbon::parse($appointment->start_time)->locale("ar_IQ")->isoFormat("h:mm A");
            $orderDesc .= " ولمدة " . $appointment->session_duration . " دقيقة ";
            $queryResult["order"] = [
                "name" => $orderName,
                "desc" => $orderDesc,
                'discount' => null,
                "price" => $appointment->total_price,
                "orderType" => "appointment",
                "appointment_id" => $appointment->id
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
            "order_id" => ["required"]
        ];

        return Validator::make(
            $fields,
            $rules,
        );
    }

    /**
     *
     */
    public function submit(Request $request, $order_id)
    {
        $orderType = $request->get("orderType");
        $this->validateIndex($orderType, $order_id);


        $validatePaymentMethod = Validator::make($request->only(["payment_method"]), ["payment_method" => "required"]);
        $discount = $request->get('discounted_price');
        $course_price = $enrolment->course_price;

        if ($validatePaymentMethod->fails()) {
            $request->session()->flash("error", "الرجاء اختيار وسيلة دفع");
            return redirect()->back();
        }

        switch ($request->get('payment_method')) {
            case "cash":
                if (empty($request->get('cash_code'))) {
                    $request->session()->flash("error", "الرجاء أضافه كود الشراء");
                    return redirect()->back();
                }

                $CashPayment = new CashController();

                return $CashPayment->pay($request->get('cash_code'), $order_id);
            case "zain_cash":

                $zainCash = new ZainCashController();

                return $zainCash->pay($discount ?? $course_price, $order_id, $orderType);
            case "credit":

                $credit = new TabadulPayment();
                $amount = intval($discount ?? $course_price);

                return $credit->createOrder($amount, $order_id, $orderType);
            default:
                return redirect()->back();

        }


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
