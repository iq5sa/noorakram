<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\CashCode;
use App\Models\Curriculum\CourseEnrolment;

class CashController extends Controller
{

    public function pay($cashCode, $order_id): array
    {
        $cashCode = CashCode::where("code", "=", $cashCode)->get();
        $enrolment = CourseEnrolment::where("order_id", "=", $order_id)->get();

        if ($cashCode->count() == 0 || $cashCode->first()->expire == 1) {
            return ["error" => "كود الشراء غير صحيح", "url" => null];

        }

        $updateEnrolment = CourseEnrolment::find($enrolment->first()->id);
        $updateEnrolment->payment_status = 'completed';
        $updateEnrolment->payment_method = 'Cash';
        $updateEnrolment->save();

        $findCode = CashCode::find($cashCode->first()->id);
        $findCode->expire = 1;
        $findCode->save();


        return ["error" => null, "url" => route('user.my_course.curriculum', ['id' => $enrolment->first()->course_id])];
    }
}
