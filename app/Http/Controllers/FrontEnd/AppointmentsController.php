<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentsController extends Controller
{
    //

    public function index(): Factory|View|Application
    {

        $language = $this->getLanguage();


        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

        $queryResult['pageHeading'] = "حجز موعد";

        $queryResult['breadcrumbImg'] = "appointment.png";
        $queryResult["availableTimes"] = $this->getAvailableTimes();
        $queryResult["availableDates"] = $this->getAvailableDates();


        return view('frontend.appointments.index', $queryResult);
    }

    private function getAvailableDates(): array
    {

        return DB::table('appointment_date_open')->select('date')->distinct()->pluck('date')->toArray();
    }

    private function getAvailableTimes(): array
    {
        return DB::table('appointment_time_open')->select('time')->distinct()->pluck('time')->toArray();
    }


    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'date' => 'required',
            'time' => 'required',
            'session_type' => 'required',
            'session_duration' => 'required',
            'name' => auth()->check() ? '' : 'required',
            'email' => auth()->check() ? '' : ['required', "email"],
            'phone_number' => auth()->check() ? '' : ['required', 'regex:/^(\+964|0)?78\d{8}$/'],
        ], [
            'subject.required' => 'يرجى كتابة موضوع الجلسة',
            'date.required' => 'التاريخ مطلوب',
            'time.required' => 'يرجى تحديد وقت الجلسة',
            'session_type.required' => 'يرجى تحديد نوع الجلسة',
            'session_duration.required' => 'يرجى تحديد مدة الجلسة',
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الالكتروني مطلوب',
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.regex' => 'رقم الهاتف غير صالح'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $subject = $request->input('subject');
        $date = $request->input('date');
        $time = $request->input('time');
        $session_type = $request->input('session_type');
        $session_duration = $request->input('session_duration');
        $name = $request->input('name') ?? auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $email = $request->input('email') ?? auth()->user()->email;
        $phone_number = $request->input('phone_number') ?? auth()->user()->contact_number;
        $user_id = auth()->check() ? auth()->user()->id : null;


        $appointment = DB::table('appointments')->insertGetId([
            'subject' => $subject,
            'date' => $date,
            'start_time' => $time,
            'end_time' => Carbon::parse($time)->addMinutes($session_duration)->format('H:i:s'),
            'session_type' => $session_type,
            'session_duration' => $session_duration,
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'user_id' => $user_id,
            'order_id' => self::generateOrderID($user_id)
        ]);

        if ($appointment) {
            $appointment = DB::table('appointments')->where('id', $appointment)->first();
            return redirect()->route('payment.checkout', ['order_id' => $appointment->order_id, "orderType" => "appointment"])->with('success', 'تم حجز الجلسة بنجاح');
            // return redirect()->back()->with('success', 'تم حجز الجلسة بنجاح');
        } else {
            return redirect()->back()->with('error', 'حدث خطأ ما يرجى المحاولة لاحقا');
        }


    }


}
