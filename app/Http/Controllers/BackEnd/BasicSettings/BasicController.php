<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\MailFromAdminRequest;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BasicController extends Controller
{
    public function favicon()
    {
        $data = DB::table('basic_settings')->select('favicon')->first();

        return view('backend.basic-settings.favicon', ['data' => $data]);
    }

    public function updateFavicon(Request $request)
    {
        $data = DB::table('basic_settings')->select('favicon')->first();

        $rules = [];

        if (!$request->filled('favicon') && is_null($data->favicon)) {
            $rules['favicon'] = 'required';
        }
        if ($request->hasFile('favicon')) {
            $rules['favicon'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('favicon')) {
            $iconName = UploadFile::update('./img/', $request->file('favicon'), $data->favicon);

            // finally, store the favicon into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['favicon' => $iconName]
            );

            $request->session()->flash('success', 'Favicon updated successfully!');
        }

        return redirect()->back();
    }


    public function logo()
    {
        $data = DB::table('basic_settings')->select('logo')->first();

        return view('backend.basic-settings.logo', ['data' => $data]);
    }

    public function updateLogo(Request $request)
    {
        $data = DB::table('basic_settings')->select('logo')->first();

        $rules = [];

        if (!$request->filled('logo') && is_null($data->logo)) {
            $rules['logo'] = 'required';
        }
        if ($request->hasFile('logo')) {
            $rules['logo'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('logo')) {
            $logoName = UploadFile::update('./img/', $request->file('logo'), $data->logo);

            // finally, store the logo into db
            DB::table('basic_settings')->updateOrInsert(
                ['logo' => $logoName]
            );

            $request->session()->flash('success', 'Logo updated successfully!');
        }

        return redirect()->back();
    }


    public function information()
    {
        $data = DB::table('basic_settings')
            ->select('website_title', 'email_address', 'contact_number', 'address')
            ->first();

        return view('backend.basic-settings.information', ['data' => $data]);
    }

    public function updateInfo(Request $request)
    {
        $rules = [
            'website_title' => 'required',
            'email_address' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'website_title' => $request->website_title,
                'email_address' => $request->email_address,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
            ]
        );

        $request->session()->flash('success', 'Information updated successfully!');

        return redirect()->back();
    }


    public function currency()
    {
        $data = DB::table('basic_settings')
            ->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
            ->first();

        return view('backend.basic-settings.currency', ['data' => $data]);
    }

    public function updateCurrency(CurrencyRequest $request)
    {
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'base_currency_symbol' => $request->base_currency_symbol,
                'base_currency_symbol_position' => $request->base_currency_symbol_position,
                'base_currency_text' => $request->base_currency_text,
                'base_currency_text_position' => $request->base_currency_text_position,
                'base_currency_rate' => $request->base_currency_rate
            ]
        );

        $request->session()->flash('success', 'Currency updated successfully!');

        return redirect()->back();
    }


    public function appearance()
    {
        $data = DB::table('basic_settings')
            ->select('primary_color', 'secondary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity')
            ->first();

        return view('backend.basic-settings.appearance', ['data' => $data]);
    }

    public function updateAppearance(Request $request)
    {
        $rules = [
            'primary_color' => 'required',
            'secondary_color' => 'required',
            'breadcrumb_overlay_color' => 'required',
            'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
                'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
                'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity
            ]
        );

        $request->session()->flash('success', 'Appearance updated successfully!');

        return redirect()->back();
    }


    public function mailFromAdmin()
    {
        $data = DB::table('basic_settings')
            ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        return view('backend.basic-settings.email.mail-from-admin', ['data' => $data]);
    }

    public function updateMailFromAdmin(MailFromAdminRequest $request)
    {
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'smtp_status' => $request->smtp_status,
                'smtp_host' => $request->smtp_host,
                'smtp_port' => $request->smtp_port,
                'encryption' => $request->encryption,
                'smtp_username' => $request->smtp_username,
                'smtp_password' => $request->smtp_password,
                'from_mail' => $request->from_mail,
                'from_name' => $request->from_name
            ]
        );

        $request->session()->flash('success', 'Mail info updated successfully!');

        return redirect()->back();
    }

    public function mailToAdmin()
    {
        $data = DB::table('basic_settings')->select('to_mail')->first();

        return view('backend.basic-settings.email.mail-to-admin', ['data' => $data]);
    }

    public function updateMailToAdmin(Request $request)
    {
        $rule = [
            'to_mail' => 'required'
        ];

        $message = [
            'to_mail.required' => 'The mail address field is required.'
        ];

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            ['to_mail' => $request->to_mail]
        );

        $request->session()->flash('success', 'Mail info updated successfully!');

        return redirect()->back();
    }


    public function breadcrumb()
    {
        $data = DB::table('basic_settings')->select('breadcrumb')->first();

        return view('backend.basic-settings.breadcrumb', ['data' => $data]);
    }

    public function updateBreadcrumb(Request $request)
    {
        $data = DB::table('basic_settings')->select('breadcrumb')->first();

        $rules = [];

        if (!$request->filled('breadcrumb') && is_null($data->breadcrumb)) {
            $rules['breadcrumb'] = 'required';
        }
        if ($request->hasFile('breadcrumb')) {
            $rules['breadcrumb'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('breadcrumb')) {
            $breadcrumbName = UploadFile::update('./img/', $request->file('breadcrumb'), $data->breadcrumb);

            // finally, store the breadcrumb into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['breadcrumb' => $breadcrumbName]
            );

            $request->session()->flash('success', 'Breadcrumb updated successfully!');
        }

        return redirect()->back();
    }


    public function plugins()
    {
        $data = DB::table('basic_settings')
            ->select('disqus_status', 'disqus_short_name', 'google_recaptcha_status', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message')
            ->first();

        return view('backend.basic-settings.plugins', ['data' => $data]);
    }

    public function updateRecaptcha(Request $request)
    {
        $rules = [
            'google_recaptcha_status' => 'required',
            'google_recaptcha_site_key' => 'required',
            'google_recaptcha_secret_key' => 'required'
        ];

        $messages = [
            'google_recaptcha_status.required' => 'The recaptcha status field is required.',
            'google_recaptcha_site_key.required' => 'The recaptcha site key field is required.',
            'google_recaptcha_secret_key.required' => 'The recaptcha secret key field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'google_recaptcha_status' => $request->google_recaptcha_status,
                'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
                'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key
            ]
        );

        $array = [
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret_key,
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_site_key
        ];

        setEnvironmentValue($array);
        Artisan::call('config:clear');

        $request->session()->flash('success', 'Recaptcha info updated successfully!');

        return redirect()->back();
    }

    public function updateDisqus(Request $request)
    {
        $rules = [
            'disqus_status' => 'required',
            'disqus_short_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'disqus_status' => $request->disqus_status,
                'disqus_short_name' => $request->disqus_short_name
            ]
        );

        $request->session()->flash('success', 'Disqus info updated successfully!');

        return redirect()->back();
    }

    public function updateWhatsApp(Request $request)
    {
        $rules = [
            'whatsapp_status' => 'required',
            'whatsapp_number' => 'required',
            'whatsapp_header_title' => 'required',
            'whatsapp_popup_status' => 'required',
            'whatsapp_popup_message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'whatsapp_status' => $request->whatsapp_status,
                'whatsapp_number' => $request->whatsapp_number,
                'whatsapp_header_title' => $request->whatsapp_header_title,
                'whatsapp_popup_status' => $request->whatsapp_popup_status,
                'whatsapp_popup_message' => $request->whatsapp_popup_message
            ]
        );

        $request->session()->flash('success', 'WhatsApp info updated successfully!');

        return redirect()->back();
    }


    public function maintenance()
    {
        $data = DB::table('basic_settings')
            ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
            ->first();

        return view('backend.basic-settings.maintenance', ['data' => $data]);
    }

    public function updateMaintenance(Request $request)
    {
        $data = DB::table('basic_settings')->select('maintenance_img')->first();

        $rules = $messages = [];

        if (!$request->filled('maintenance_img') && is_null($data->maintenance_img)) {
            $rules['maintenance_img'] = 'required';

            $messages['maintenance_img.required'] = 'The maintenance image field is required.';
        }
        if ($request->hasFile('maintenance_img')) {
            $rules['maintenance_img'] = new ImageMimeTypeRule();
        }

        $rules['maintenance_status'] = 'required';
        $rules['maintenance_msg'] = 'required';

        $messages['maintenance_msg.required'] = 'The maintenance message field is required.';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('maintenance_img')) {
            $imageName = UploadFile::update('./img/', $request->file('maintenance_img'), $data->maintenance_img);
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'maintenance_img' => $request->hasFile('maintenance_img') ? $imageName : $data->maintenance_img,
                'maintenance_status' => $request->maintenance_status,
                'maintenance_msg' => Purifier::clean($request->maintenance_msg),
                'bypass_token' => $request->bypass_token
            ]
        );

        if ($request->maintenance_status == 1) {
            $link = route('service_unavailable');

            Artisan::call('down --redirect=' . $link . ' --secret="' . $request->bypass_token . '"');
        } else {
            Artisan::call('up');
        }

        $request->session()->flash('success', 'Maintenance Info updated successfully!');

        return redirect()->back();
    }


    public function footerLogo()
    {
        $data = DB::table('basic_settings')->select('footer_logo')->first();

        return view('backend.basic-settings.footer-logo', ['data' => $data]);
    }

    public function updateFooterLogo(Request $request)
    {
        $data = DB::table('basic_settings')->select('footer_logo')->first();

        $rules = [];

        if (!$request->filled('footer_logo') && is_null($data->footer_logo)) {
            $rules['footer_logo'] = 'required';
        }
        if ($request->hasFile('footer_logo')) {
            $rules['footer_logo'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('footer_logo')) {
            $logoName = UploadFile::update('./img/', $request->file('footer_logo'), $data->footer_logo);

            // finally, store the footer-logo into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['footer_logo' => $logoName]
            );

            $request->session()->flash('success', 'Footer logo updated successfully!');
        }

        return redirect()->back();
    }


    public function advertiseSettings()
    {
        $data = DB::table('basic_settings')->select('google_adsense_publisher_id')->first();

        return view('backend.advertisement.settings', ['data' => $data]);
    }

    public function updateAdvertiseSettings(Request $request)
    {
        $rule = [
            'google_adsense_publisher_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            ['google_adsense_publisher_id' => $request->google_adsense_publisher_id]
        );

        $request->session()->flash('success', 'Advertise settings updated successfully!');

        return redirect()->back();
    }
}
