<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Journal\Blog;
use App\Models\Language;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AdminController extends Controller
{
    public function login()
    {
        return view('backend.login');
    }

    public function authentication(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if (
            Auth::guard('admin')->attempt([
                'username' => $request->username,
                'password' => $request->password,
            ])
        ) {
            $authAdmin = Auth::guard('admin')->user();

            // check whether the admin's account is active or not
            if ($authAdmin->status == 0) {
                $request->session()->flash('alert', 'Sorry, your account has been deactivated!');

                // logout auth admin as condition not satisfied
                Auth::guard('admin')->logout();

                return redirect()->back();
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            return redirect()->back()->with('alert', 'Oops, username or password does not match!');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // invalidate the admin's session
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }

    public function forgetPassword()
    {
        return view('backend.forget-password');
    }

    public function sendMail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('admin'),
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // create a new password and store it in db
        $newPassword = uniqid();

        $admin = Admin::where('email', $request->email)->first();

        $admin->update([
            'password' => Hash::make($newPassword),
        ]);

        // send newly created password to admin via email
        $info = DB::table('basic_settings')
            ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        // initialize a new mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            $mail->isSMTP();
            $mail->Host = $info->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $info->smtp_username;
            $mail->Password = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = $info->smtp_port;
        }

        // finally add other informations and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($request->email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body = 'Hello '.$admin->first_name.',<br/><br/>Your password has reset. Your new password is: '.$newPassword.'<br/><br/>Now, you can login with your new password. You can change your password later.<br/><br/>Thank you.';

            $mail->send();

            $request->session()->flash('success', 'A mail has been sent to your email address.');
        } catch (Exception $e) {
            $request->session()->flash('warning', 'Mail could not be sent. Mailer Error: '.$mail->ErrorInfo);
        }

        return redirect()->back();
    }

    public function redirectToDashboard()
    {
        $language = Language::query()->where('is_default', '=', 1)->first();

        $information['totalCourses'] = Course::query()->count();
        $information['totalCourseCategories'] = $language->courseCategory()->count();
        $information['totalCourseEnrolments'] = CourseEnrolment::query()->count();
        $information['totalInstructors'] = $language->instructor()->count();
        $information['totalBlog'] = Blog::query()->count();
        $information['totalRegisteredUsers'] = User::query()->count();

        $monthWiseTotalIncomes = DB::table('course_enrolments')
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
            ->where('payment_status', '=', 'completed')
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $monthWiseTotalEnrolments = DB::table('course_enrolments')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_enrolment'))
            ->where('payment_status', '=', 'completed')
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $months = [];
        $incomes = [];
        $enrolments = [];

        for ($i = 1; $i <= 12; $i++) {
            // get all 12 months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            // get all 12 months's income
            $incomeFound = false;

            foreach ($monthWiseTotalIncomes as $incomeInfo) {
                if ($incomeInfo->month == $i) {
                    $incomeFound = true;
                    array_push($incomes, $incomeInfo->total);
                    break;
                }
            }

            if ($incomeFound == false) {
                array_push($incomes, 0);
            }

            // get all 12 months's course enrolment
            $enrolmentFound = false;

            foreach ($monthWiseTotalEnrolments as $enrolmentInfo) {
                if ($enrolmentInfo->month == $i) {
                    $enrolmentFound = true;
                    array_push($enrolments, $enrolmentInfo->total_enrolment);
                    break;
                }
            }

            if ($enrolmentFound == false) {
                array_push($enrolments, 0);
            }
        }

        $information['months'] = $months;
        $information['incomes'] = $incomes;
        $information['enrolments'] = $enrolments;

        return view('backend.admin.dashboard', $information);
    }

    public function editProfile()
    {
        $adminInfo = Auth::guard('admin')->user();

        return view('backend.admin.edit-profile', compact('adminInfo'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [];

        if (! $request->filled('image') && is_null($admin->image)) {
            $rules['image'] = 'required';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = new ImageMimeTypeRule();
        }

        $rules['username'] = [
            'required',
            Rule::unique('admins')->ignore($admin->id),
        ];

        $rules['email'] = [
            'required',
            'email:rfc,dns',
            Rule::unique('admins')->ignore($admin->id),
        ];

        $rules['first_name'] = 'required';

        $rules['last_name'] = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update('./img/admins/', $request->file('image'), $admin->image);
        }

        $admin->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'image' => $request->hasFile('image') ? $imageName : $admin->image,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        $request->session()->flash('success', 'Profile updated successfully!');

        return redirect()->back();
    }

    public function changePassword()
    {
        return view('backend.admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('admin'),
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 400);
        }

        $admin = Auth::guard('admin')->user();

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        $request->session()->flash('success', 'Password updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }
}
