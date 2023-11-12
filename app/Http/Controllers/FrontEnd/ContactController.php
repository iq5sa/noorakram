<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ContactController extends Controller
{
    public function contact()
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

        $queryResult['pageHeading'] = "تواصل معنا";

        $queryResult['breadcrumbImg'] = "contact.png";

        return view('frontend.contact', $queryResult);
    }

    public function sendMail(Request $request)
    {
        $info = Basic::select('google_recaptcha_status', 'to_mail')->firstOrFail();

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];

        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $msgs = [
            "name.required" => "الاسم الثلاثي مطلوب",
            "email.required" => " البريد الإلكتروني مطلوب",
            "subject.required" => " الموضوع مطلوب",
            "message.required" => " نص الرسالة مطلوب",
            "email.email" => "البريد الإلكتروني غير صحيح",
        ];


        $validator = Validator::make($request->all(), $rules, $msgs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $from = $request->email;
        $name = $request->name;
        $to = $info->to_mail;
        $subject = $request->subject;
        $message = $request->message;

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        try {
            $mail->setFrom($from, $name);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            $request->session()->flash('success', 'تم إرسال الرسالة بنجاح');
        } catch (Exception $e) {
            $request->session()->flash('error', 'لم يتم إرسال البريد الإلكتروني لجود خلل فني.');
        }

        return redirect()->back();
    }
}
