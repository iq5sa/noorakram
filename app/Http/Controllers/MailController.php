<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailVerification;
use App\Mail\SendResetPasswordEmail;
use Exception;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //


    public function verificationEmail($to, $url, $name = null)
    {

        $mailData = [
            'name' => $name,
            'url' => $url,
        ];

        try {
            Mail::to($to)->send(new SendEmailVerification($mailData));

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();

        }


    }


    public function resetPassword($to, $url, $name)
    {

        $mailData = [

            'name' => $name,
            'url' => $url,
        ];

        try {
            Mail::to($to)->send(new SendResetPasswordEmail($mailData));

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();

        }


    }
}
