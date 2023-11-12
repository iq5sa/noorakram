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
//  public function enrolmentProcess(Request $request, $course_id)
//  {
//    $authUser = Auth::guard('web')->user();
//    $enrollment = CourseEnrolment::where("user_id", "=", $authUser->id)
//      ->where("course_id", "=", $course_id)->first();
//    $paymentMethod = PaymentMethods::find($request->get("gateway"));
//    if (!empty($enrollment) && $enrollment->payment_status == 'pending') {
//      $order_id = $this->generateOrderID(Auth::id());
//      $updateOrderId = CourseEnrolment::find($enrollment->id);
//      $updateOrderId->order_id = $order_id;
//      $updateOrderId->save();
//
//      return $this->masterCardPayment([
//        "returnUrl" => route('course-enrolment.complete', ["order_id" => $updateOrderId->order_id]),
//        "amount" => $updateOrderId->course_price,
//        "order_id" => $updateOrderId->order_id]);
//
//    } else {
//
//      $selectedCourse = Course::find($course_id)->toArray();
//
//      $enrol = new EnrolmentController();
//      $arrData = array(
//        'course_id' => $course_id,
//        'paymentStatus' => 'pending',
//        "paymentMethod" => $paymentMethod->name,
//        "gatewayType" => "online",
//        "coursePrice" => $selectedCourse["current_price"],
//        "currencyText" => "IQD",
//
//      );
//
//      $enrolSaved = $enrol->storeData($arrData);
//      $returnUrl = route('course-enrolment.complete', ["order_id" => $enrolSaved->order_id]);
//
//      return $this->masterCardPayment([
//        "returnUrl" => $returnUrl,
//        "amount" => $enrolSaved->course_price,
//        "order_id" => $enrolSaved->order_id]);
//
//
//    }
//
//
//    //$status = CourseEnrolment::where('user_id', $authUser->id)->where('course_id', $course_id)->first();
//
//
////    if ($status == 'pending') {
////      $request->session()->flash('warning', 'طلب التسجيل الخاص بك لهذه الدورة معلق.');
////      return redirect()->back();
////    }
//
//
////    $paymentGatewayResponse = $this->masterCardPayment([
////      "returnUrl" => route('course-enrolment.complete', ["order_id" => $enrolmentInfo["order_id"]]),
////      "amount" => $enrolmentInfo["course_price"],
////      "order_id" => $enrolmentInfo["order_id"]
////    ]);
////
////
////
////
////    return $paymentGatewayResponse;
//
//
////
////    if ($paymentMethod->name == "CreditCard") {
////
////
////
////    } elseif ($request->gateway == "zainCash") {
////
////      $zaincash = new \Hawkiq\LaravelZaincash\Services\ZainCash();
////
////      $amount = $selectedCourse->current_price;
////
////      $service_type = "course";
////
////
////      $payload = $zaincash->request($amount, $service_type, $order_id);
////      return $payload;
////    } elseif ($request->gateway == "cash") {
////
////
//////
////
////    }
//
//
//  }
//
//  public function masterCardPayment($data)
//  {
//    $paymentGateway = new TabadulPayment();
//
//    $response = $paymentGateway->sendRequest([
//      "amount" => $data["amount"],
//      "orderId" => $data["order_id"],
//      "returnUrl" => $data["returnUrl"]
//    ]);
//
//    $response = json_decode($response);
//    if ($response->errorCode == 0) {
//      $enrol = CourseEnrolment::where("order_id", "=", $data["order_id"]);
//      if ($enrol->count() !== 0) {
//        $enrolId = $enrol->first()->id;
//        $updateEnrolTransactionId = CourseEnrolment::find($enrolId);
//        $updateEnrolTransactionId->transactionId = $response->orderId;
//        $updateEnrolTransactionId->save();
//      }
//
//    }
//
//    return $response->errorCode == 0 ? redirect($response->formUrl) : redirect('/404');
//
//  }
//
//  public function payPendingOrder($id)
//  {
//    $order_id = $id;
//    $transaction_id = Orders::count() + 1;
//    $qiPayment = new TabadulPayment();
//    $selectedOrder = Orders::where("order_id", "=", $id)->first();
//
//    $response = $qiPayment->sendRequest([
//      "amount" => $selectedOrder->amount,
//      "orderId" => $transaction_id,
//      "returnUrl" => \route('course-enrolment.complete', ["id" => $transaction_id])
//    ]);
//
//    $responseJson = json_decode($response);
//
//
//    if ($responseJson->errorCode == 0 && !empty($responseJson->orderId)) {
//      //make order
//      $order = new Orders();
//      $order->order_id = $order_id;
//      $order->payment_method = $selectedOrder->payment_method;
//      $order->amount = $selectedOrder->amount;
//      $order->gateway_order_id = $responseJson->orderId;
//      $order->order_type = "course";
//      $order->enrolment_id = $selectedOrder->enrolment_id;
//      $order->user_id = Auth::id();
//      $order->save();
//      return redirect($responseJson->formUrl);
//    } else {
//      return redirect()->back()->with("حدث خطأ إثناء عملية الدفع");
//    }
//
//  }
//
//  public function complete(Request $request, $course_id)
//  {
//
//    if ($request->auth_status == "4" || $request->token !== "") {
//      $enrol = new EnrolmentController();
//
//      // do calculation
//      $calculatedData = $enrol->calculation($request, $course_id);
//
//      $currencyInfo = $this->getCurrencyInfo();
//
//      // store attachment in local storage
//      if ($request->hasFile('attachment')) {
//        $attachmentName = UploadFile::store('./file/attachments/', $request->file('attachment'));
//      }
//
//      $arrData = array(
//        'course_id' => $course_id,
//        'coursePrice' => $calculatedData['coursePrice'],
//        'discount' => $calculatedData['discount'],
//        'grandTotal' => $calculatedData['grandTotal'],
//        'currencyText' => $currencyInfo->base_currency_text,
//        'currencyTextPosition' => $currencyInfo->base_currency_text_position,
//        'currencySymbol' => $currencyInfo->base_currency_symbol,
//        'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
//        'paymentMethod' => "",
//        'gatewayType' => 'offline',
//        'paymentStatus' => 'completed',
//        'attachmentFile' => $request->exists('attachment') ? $attachmentName : null
//      );
//
//      // store the course enrolment information in database
//      $enrol->storeData($arrData);
//
////      route("user.my_course.curriculum",["id"=>$course_id])
////
//      return redirect()->route('user.my_course.curriculum', ['id' => $course_id]);
//    } else {
//      return redirect()->route('course_enrolment.cancel', ['id' => $course_id, 'via' => 'online']);
//
//    }
//  }
}
