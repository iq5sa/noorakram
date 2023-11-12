<?php

namespace App\Http\Controllers\FrontEnd\Curriculum\Enrol;

use App\Http\Controllers\Controller;

class FreeCourseEnrolController extends Controller
{
    public function process($courseId)
    {
        $enrol = new EnrolmentController();

        $arrData = array(
            'course_id' => $courseId,
            'paymentStatus' => 'completed'
        );

        // store the course enrolment information in database
        $enrolmentInfo = $enrol->storeData($arrData);

        // generate an invoice in pdf format
        //$invoice = $enrol->generateInvoice($enrolmentInfo, $courseId);

        // then, update the invoice field info in database
        //$enrolmentInfo->update(['invoice' => $invoice]);

        // send a mail to the customer with the invoice
        $enrol->sendMail($enrolmentInfo);

        return redirect()->route('course.enrol.complete', ["order_id" => $enrolmentInfo->order_id]);
    }
}
