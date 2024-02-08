<?php


namespace App\Http\Controllers\FrontEnd\PaymentGateway;


use App\Http\Controllers\Controller;
use App\Models\Curriculum\CourseEnrolment;
use Hawkiq\LaravelZaincash\Services\ZainCash;
use Illuminate\Http\Request;

class ZainCashController extends Controller
{
    public function pay($amount, $order_id, $orderType)
    {
        $zainCashInstance = new ZainCash();
        return $zainCashInstance->request($amount, $orderType, $order_id);
    }


    public function redirect(Request $request)
    {
        $token = $request->get("token");

        if (isset($token)) {
            $zaincash = new ZainCash();
            $result = $zaincash->parse($token);


            if ($result->status == "success") {
                $enrolment = CourseEnrolment::where("order_id", "=", $result->orderid)->get();
                $updateEnrolment = CourseEnrolment::find($enrolment->first()->id);
                $updateEnrolment->payment_status = "completed";
                $updateEnrolment->payment_method = "ZainCash";
                $updateEnrolment->transactionToken = $result->id;
                $updateEnrolment->save();


                if ($enrolment->count() !== 0) {
                    return redirect()->route("payment.success", ['order_id' => $updateEnrolment->order_id])->with("success", 'تمت عملية الدفع بنجاح');
                }

                return redirect()->route("payment.checkout", ["order_id" => $result->orderid,
                        "orderType" => "course"]
                )->with("error", "حدث خطا ما يرجى المحاولة لاحقاً");

            } else {

                return redirect()->route("payment.checkout", ["order_id" => $result->orderid,
                        "orderType" => "course"]
                )->with("error", "لا توجد اموال كافي في حسابك");
            }

        }
    }


}

