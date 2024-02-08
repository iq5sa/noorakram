<?php

namespace App\Http\Controllers\FrontEnd\Curriculum\Enrol;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Course;

class PremiumCourseEnrolController extends Controller
{


    public function process($courseId)
    {

        $enrol = new EnrolmentController();
        $course = Course::where("id", "=", $courseId)->get()->first();

        $arrData = array(
            'course_id' => $courseId,
            'paymentStatus' => 'pending',
            "coursePrice" => $course->current_price,
            "currencyText" => "IQD",
            "currencySymbol" => "د.ع",

        );

        // store the course enrolment information in database
        $enrolmentInfo = $enrol->storeData($arrData);
        \request()->session()->put("enrolment", $enrolmentInfo);

        return redirect()->route('payment.checkout', ['order_id' => $enrolmentInfo->order_id, "orderType" => "course"]);
    }

}
