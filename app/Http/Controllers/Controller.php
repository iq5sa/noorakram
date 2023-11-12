<?php

namespace App\Http\Controllers;

use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\PageHeading;
use App\Models\Language;
use App\Models\Subscriber;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getBreadcrumb()
    {
        return Basic::select('breadcrumb')->firstOrFail();
    }

    public static function generateOrderID($userIdentifier): string
    {
        // Get the current timestamp
        $timestamp = time();

        $randomString = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, 6);

        return $timestamp . $randomString . $userIdentifier;
    }

    public function getCurrencyInfo()
    {
        $baseCurrencyInfo = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
            ->firstOrFail();

        return $baseCurrencyInfo;
    }

    public function getLanguage()
    {
        // get the current locale of this system
        if (Session::has('currentLocaleCode')) {
            $locale = Session::get('currentLocaleCode');
        }


        if (empty($locale)) {
            $language = Language::where('is_default', 1)->first();
        } else {
            $language = Language::where('code', $locale)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->firstOrFail();
            }
        }


        return $language;
    }

    public function getPageHeading($language)
    {
        if (URL::current() == Route::is('courses')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('courses_page_title')->first();
        } else if (URL::current() == Route::is('course_details')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('course_details_page_title')->first();
        } else if (URL::current() == Route::is('instructors')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('instructors_page_title')->first();
        } else if (URL::current() == Route::is('blogs')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('blog_page_title')->first();
        } else if (URL::current() == Route::is('blog_details')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('blog_details_page_title')->first();
        } else if (URL::current() == Route::is('faqs')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('faq_page_title')->first();
        } else if (URL::current() == Route::is('contact')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('contact_page_title')->first();
        } else if (URL::current() == Route::is('user.login')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('login_page_title')->first();
        } else if (URL::current() == Route::is('user.forget_password')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('forget_password_page_title')->first();
        } else if (URL::current() == Route::is('user.signup')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('signup_page_title')->first();
        } else if (URL::current() == Route::is('home.aboutPage')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('about_page_title')->first();
        } else if (URL::current() == Route::is('home.termsPage')) {
            $pageHeading = PageHeading::where('language_id', $language->id)->select('terms_page_title')->first();
        }

        return $pageHeading ?? null;
    }

    public function changeLanguage(Request $request): RedirectResponse
    {

        // put the selected language in session
        $langCode = $request['code'];

        $request->session()->put('currentLocaleCode', $langCode);

        return redirect()->back();
    }

    public function serviceUnavailable(): View
    {
        $info = DB::table('basic_settings')->select('maintenance_img', 'maintenance_msg')->first();

        return view('errors.503', compact('info'));
    }

    public function storeSubscriber(Request $request): JsonResponse
    {
        $rules = [
            'email_id' => 'required|email:rfc,dns|unique:subscribers'
        ];

        $messages = [
            'email_id.required' => 'يرجى ادخال بريدك الالكتروني',
            'email_id.unique' => 'البريد الالكتروني الذي ادخلته موجود من قبل!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()
            ], 400);
        }

        Subscriber::create($request->all());

        return Response::json([
            'success' => 'تم اشتراكك في القائمة البريدية بنجاح شكرا لك.'
        ], 200);
    }

    function uniqueOrderNumber($userId): string
    {
        // Get the current timestamp
        $timestamp = time();


        $randomNumber = rand(1000, 9999);

        return $userId . $timestamp . $randomNumber;
    }
}
