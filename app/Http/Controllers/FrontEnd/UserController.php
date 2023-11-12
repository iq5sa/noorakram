<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\UserProfileRequest;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseInformation;
use App\Models\Curriculum\Lesson;
use App\Models\Curriculum\LessonContent;
use App\Models\Curriculum\LessonQuiz;
use App\Models\Curriculum\QuizScore;
use App\Models\Language;
use App\Models\LessonComplete;
use App\Models\LessonContentComplete;
use App\Models\User;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UserController extends Controller
{

    public function validateLogin($credentials): \Illuminate\Validation\Validator
    {
        $info = DB::table('basic_settings')->select('google_recaptcha_status')->first();

        $rules = [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ];


        $messages = [
            'email.required' => "البريد الإلكتروني مطلوب",
            'email.email' => "البريد الإلكتروني غير صحيح",
            'password.required' => "كلمة المرور مطلوبة",
        ];


        return Validator::make($credentials, $rules, $messages);


    }

    public function loginSubmit(Request $request)
    {
        if ($request->session()->has('redirectTo')) {
            $redirectURL = $request->session()->get('redirectTo');
        } else {
            $redirectURL = null;
        }


        // get the email and password which has provided by the user
        $credentials = $request->only('email', 'password');
        $validator = $this->validateLogin($credentials);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // login attempt
        if (Auth::guard('web')->attempt($credentials)) {
            $authUser = Auth::guard('web')->user();

            // first, check whether the user's email address verified or not
            if ($authUser->email_verified_at == null) {
                $request->session()->flash('error', 'يرجى التحقق من عنوان البريد الإلكتروني الخاص بك.');

                // logout auth user as condition not satisfied
                Auth::guard('web')->logout();

                return redirect()->route('user.login');
            }

            // second, check whether the user's account is active or not
            if ($authUser->status == 0) {
                $request->session()->flash('error', 'عفوا، حسابك غير مُفعل');

                // logout auth user as condition not satisfied
                Auth::guard('web')->logout();


                return redirect()->route('user.login');
            }

            // otherwise, redirect auth user to next url
            if ($redirectURL == null) {
                return redirect()->route('user.dashboard');
            } else {
                // before, redirect to next url forget the session value
                $request->session()->forget('redirectTo');

                return redirect($redirectURL);
            }
        }

        // return __('auth.login.incorrect.details');
        $error = __('auth.login.incorrect.details');
        $request->session()->flash('error', $error);

        return redirect()->back()->withInput();


//        return redirect()->back()->withErrors($errorMsg)->withInput(['email' => $request->old('email'), 'password' => $request->old('email')]);

    }

    public function forgetPassword()
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_forget_password', 'meta_description_forget_password')->first();

        $queryResult['pageHeading'] = $this->getPageHeading($language);

        $queryResult['bgImg'] = $this->getBreadcrumb();

        return view('frontend.forget-password', $queryResult);
    }

    public function sendMail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('user')
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        // first, get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->first_name . ' ' . $user->last_name;

        $link = '<a href=' . url("user/reset-password") . '>اضغط هنا</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

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
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();

            $request->session()->flash('success', ' تم إرسال البريد الإلكتروني بنجاح، تحقق من بريدك ');
        } catch (Exception $e) {
            $request->session()->flash('error', 'حدثت مشكلة عند الإرسال يرجى المحاولة لحقاً');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        return redirect()->back();
    }

    public function resetPassword()
    {
        $bgImg = $this->getBreadcrumb();

        return view('frontend.reset-password', compact('bgImg'));
    }

    public function resetPasswordSubmit(Request $request)
    {
        // get the user email from session
        $emailAddress = $request->session()->get('userEmail');

        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'كلمة المرور غير مطابقة',
            'new_password_confirmation.required' => '.حقل تأكيد كلمة السر مطلوب',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = User::where('email', $emailAddress)->first();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', '.تم تحديث كلمة المرور بنجاح');

        return redirect()->route('user.login');
    }

    public function signup()
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_signup', 'meta_description_signup')->first();

        $queryResult['pageHeading'] = "إنشاء حساب";

        $queryResult['breadcrumbImg'] = "signup.png";

        $queryResult['recaptchaInfo'] = DB::table('basic_settings')->select('google_recaptcha_status')->first();

        return view('frontend.signup', $queryResult);
    }

    public function signupSubmit(Request $request)
    {
        $info = DB::table('basic_settings')->select('google_recaptcha_status')->first();

        $rules = [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $messages = [
            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب',
            'username.required' => "اسم المستخدم مطلوب",
            'username.unique' => "اسم المستخدم محجوز",
            'email.required' => "البريد الإلكتروني مطلوب",
            'email.unique' => "البريد الإلكتروني مستخدم",
            'password.required' => "كلمة المرور مطلوبة",
            'password.confirmed' => "كلمة المرور غير متطابقة",
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // first, generate a random string
        $randStr = Str::random(20);

        // second, generate a token
        $token = md5($randStr . $request->username . $request->email);

        $user->verification_token = $token;
        $user->save();

        // send a mail to user for verify his/her email address
        $this->sendVerificationMail($request, $token);

        return redirect()->back();
    }

    public function sendVerificationMail(Request $request, $token)
    {
        // first get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $link = '<a href=' . url("user/signup-verify/" . $token) . '>اضغط هنا</a>';

        $mailBody = str_replace('{username}', $request->username, $mailBody);
        $mailBody = str_replace('{verification_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

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

        // finally add other information and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($request->email);

            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();

            $request->session()->flash('success', 'تم ارسال رمز التحقق الى بريدك الالكتروني. يرجى التحقق من صندوق البريد');
        } catch (Exception $e) {
            $request->session()->flash('error', 'حدث خطأ ما عند ارسال رمز التأكيد');
        }

        return;
    }

    public function signupVerify(Request $request, $token)
    {
        try {
            $user = User::where('verification_token', $token)->firstOrFail();

            // after verify user email, put "null" in the "verification token"
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'verification_token' => null
            ]);

            $request->session()->flash('success', 'تم تأكيد كلمة المرور');

            // after email verification, authenticate this user
            Auth::guard('web')->login($user);

            return redirect()->route('user.dashboard');
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('error', 'لم نتمكن من تأكيد بريدك الالكتروني');

            return redirect()->route('user.signup');
        }
    }

    public function login(Request $request)
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_login', 'meta_description_login')->first();

        $queryResult['pageHeading'] = "تسجيل الدخول";

        $queryResult['breadcrumbImg'] = "signup.png";

        $queryResult['bgImg'] = $this->getBreadcrumb();

        // when user have to redirect to checkout page after login.
        if ($request->input('redirectPath') == 'course_details') {
            $url = url()->previous();
        }

        // when user have to redirect to course details page after login.
        if (isset($url)) {
            $request->session()->put('redirectTo', $url);
        }

        $queryResult['recaptchaInfo'] = DB::table('basic_settings')->select('google_recaptcha_status')->first();

        return view('frontend.login', $queryResult);
    }

    public function redirectToDashboard()
    {
        $queryResult['pageHeading'] = "لوحة ألإدارة";
        $queryResult['breadcrumbImg'] = "courses.png";

        $user = Auth::guard('web')->user();

        $queryResult['authUser'] = $user;

        return view('frontend.user.dashboard', $queryResult);
    }

    public function myCourses()
    {
        $language = $this->getLanguage();

        $queryResult['breadcrumbImg'] = "courses.png";
        $queryResult['pageHeading'] = "courses.png";

        $user = Auth::guard('web')->user();

        $enrols = $user->courseEnrol()->where('payment_status', 'completed')->orderByDesc('id')->get();

        $enrols->map(function ($enrol) use ($language) {
            $course = $enrol->course()->first();
            $courseInfo = $course->information()->where('language_id', $language->id)->first();

            if (empty($courseInfo)) {
                $language = Language::where('is_default', 1)->first();
                $courseInfo = $course->information()->where('language_id', $language->id)->first();
            }

            $enrol['title'] = CourseInformation::query()->where('language_id', '=', $language->id)
                ->where('course_id', '=', $course->id)
                ->pluck('title')
                ->first();

            $enrol['slug'] = CourseInformation::query()->where('language_id', '=', $language->id)
                ->where('course_id', '=', $course->id)
                ->pluck('slug')
                ->first();

            $module = $courseInfo->module()->where('status', 'published')->first();
            $lesson = !empty($module) ? $module->lesson()->where('status', 'published')->first() : NULL;
            $enrol['lesson_id'] = !empty($lesson) ? $lesson->id : NULL;
        });

        $queryResult['enrolments'] = $enrols;

        return view('frontend.user.my-courses', $queryResult);
    }

    public function purchaseHistory()
    {
        $language = $this->getLanguage();

        $queryResult['bgImg'] = $this->getBreadcrumb();

        $user = Auth::guard('web')->user();

        $enrols = $user->courseEnrol()->orderByDesc('id')->get();

        $enrols->map(function ($enrol) use ($language) {
            $course = $enrol->course()->first();
            $courseInfo = $course->information()->where('language_id', $language->id)->first();

            if (empty($courseInfo)) {
                $language = Language::where('is_default', 1)->first();
                $courseInfo = $course->information()->where('language_id', $language->id)->first();
            }

            $enrol['title'] = $courseInfo->title;
            $enrol['slug'] = $courseInfo->slug;
        });

        $queryResult['allPurchase'] = $enrols;

        return view('frontend.user.purchase-history', $queryResult);
    }

    public function curriculum($id, Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()) {
            return redirect()->route('user.login');
        }

        $language = $this->getLanguage();

        $course = Course::find($id);
        if ($course == null) {
            abort(404);
        }
        $enrollment = CourseEnrolment::where("user_id", "=", Auth::id())->where("course_id", "=", $id)->get();
        if ($enrollment->count() == 0) {
            abort(404);
        }

        if ($enrollment->first()->payment_status !== "completed") {
            abort(404);
        }

        $queryResult['certificateStatus'] = $course->certificate_status;

        $courseInfo = $course->information()->where('language_id', $language->id)->first();
        if (empty($courseInfo)) {
            $language = Language::where('is_default', '=', 1)->first();
            $courseInfo = $course->information()->where('language_id', $language->id)->first();
        }
        $queryResult['courseTitle'] = $courseInfo->title;

        $modules = $courseInfo->module()->where('status', 'published')->orderBy('serial_number', 'asc')->get();

        $lessonId = $request['lesson_id'];

        // put lesson id into session to use it in middleware
        $request->session()->put('lessonId', $lessonId);

        $lesson = Lesson::find($lessonId);

        if (!empty($lesson)) {
            $queryResult['lessonTitle'] = $lesson->title;
            $queryResult['lessonContents'] = $lesson->content()->orderBy('order_no', 'asc')->get();

            $queryResult['quizzes'] = $lesson->quiz()->get();
        } else {

            $lesson = $modules->first()->lesson->first();

            return redirect()->to(route("user.my_course.curriculum", ["id" => $id]) . "?lesson_id=$lesson->id");
        }

        // update lesson completion status
        $lessonCompleted = false;

        // when certificate system is enabled then execute this code
        if (!empty($lesson)) {
            if ($course->certificate_status == 1) {
                $totalVideo = $lesson->content()->where('type', 'video')->count();
                $totalQuiz = $lesson->content()->where('type', 'quiz')->count();

                if ($course->video_watching == 0 && ($totalVideo > 0 && $totalQuiz == 0)) {
                    // if video watching disabled and, lesson has only video then complete the lesson
                    $lessonCompleted = true;
                } else if ($course->quiz_completion == 0 && ($totalQuiz > 0 && $totalVideo == 0)) {
                    // if quiz completion disabled and, lesson has only quiz then complete the lesson
                    $lessonCompleted = true;
                } else if (($course->video_watching == 0 && $course->quiz_completion == 0) && ($totalVideo > 0 && $totalQuiz > 0)) {
                    // if both video watching & quiz completion disabled and, lesson has both video & quiz then complete the lesson
                    $lessonCompleted = true;
                } else if ($totalVideo == 0 && $totalQuiz == 0) {
                    // if lesson does not have both video & quiz then complete the lesson
                    $lessonCompleted = true;
                }
            } else {
                // when certificate system is disabled then execute this code
                $lessonCompleted = true;
            }

            if ($lessonCompleted == true) {
                $lcCount = LessonComplete::where('user_id', Auth::guard('web')->id())->where('lesson_id', $lessonId)->count();
                if ($lcCount == 0) {
                    $lc = new LessonComplete();
                    $lc->user_id = Auth::guard('web')->id();
                    $lc->lesson_id = $lessonId;
                    $lc->save();
                }
            }
        }

        $modules->map(function ($module) {
            $module['lessons'] = $module->lesson()->where('status', 'published')->orderBy('serial_number', 'asc')->get();
        });

        $queryResult['modules'] = $modules;

        return view('frontend.user.course-curriculum', $queryResult);
    }

    public function downloadFile($id, Request $request)
    {
        $lessonContent = LessonContent::find($id);

        $pathToFile = './file/lesson-contents/' . $lessonContent->file_unique_name;

        try {
            return response()->download($pathToFile, $lessonContent->file_original_name);
        } catch (FileNotFoundException $e) {
            $request->session()->flash('error', 'عفوا الملف لم يعد موجود بعد الان!');

            return redirect()->back();
        }
    }

    public function checkAns(Request $request)
    {
        $id = $request['quizId'];
        $answers = $request['answers'];

        $quiz = LessonQuiz::find($id);
        $qas = json_decode($quiz->answers);

        // find out how many right answer has been selected by admin
        $rightAnsCount = 0;

        foreach ($qas as $qa) {
            if ($qa->rightAnswer == 1) {
                $rightAnsCount++;
            }
        }

        // find out how many correct answer has been given by user
        $correctAnsCount = 0;

        foreach ($answers as $ans) {
            foreach ($qas as $qa) {
                if ($ans == $qa->option && $qa->rightAnswer == 1) {
                    $correctAnsCount++;
                }
            }
        }

        if (($rightAnsCount == $correctAnsCount) && (count($answers) == $rightAnsCount)) {
            return response()->json(['status' => 'Correct'], 200);
        } else {
            return response()->json(['status' => 'Incorrect'], 200);
        }
    }

    public function storeQuizScore(Request $request)
    {
        $authUser = Auth::guard('web')->user();
        $courseId = $request['courseId'];
        $lessonId = $request['lessonId'];

        QuizScore::updateOrCreate(
            ['user_id' => $authUser->id, 'course_id' => $courseId, 'lesson_id' => $lessonId],
            ['score' => $request['score']]
        );

        return response()->json(['message' => 'تم تخزين نتيجة الاختبار بنجاح.'], 200);
    }

    public function contentCompletion(Request $request)
    {
        // update lesson-content completion status
        $id = $request['id'];

        $content = LessonContent::find($id);

        if ($content->type == 'video') {
            $lccCount1 = LessonContentComplete::where('user_id', Auth::guard('web')->id())->where('lesson_id', $content->lesson_id)->where('lesson_content_id', $id)->where('type', 'video')->count();
            if ($lccCount1 == 0) {
                $lcc = new LessonContentComplete;
                $lcc->user_id = Auth::guard('web')->id();
                $lcc->lesson_id = $content->lesson_id;
                $lcc->lesson_content_id = $id;
                $lcc->type = 'video';
                $lcc->save();
            }
        }

        // update lesson completion status
        $videoCompleted = false;
        $quizCompleted = false;
        $lessonCompleted = false;

        $courseId = (int)$request['courseId'];
        $course = Course::find($courseId);

        $lessonId = (int)$request['lessonId'];
        $lesson = Lesson::find($lessonId);

        // if video watching enabled then execute this code
        if ($course->video_watching == 1) {
            $totalVideo = $lesson->content()->where('type', 'video')->count();

            if ($totalVideo > 0) {
                $totalCompletedVideo = LessonContentComplete::where('lesson_id', $lessonId)->where('user_id', Auth::guard('web')->user()->id)->where('type', 'video')->count();

                if ($totalVideo <= $totalCompletedVideo) {
                    $videoCompleted = true;
                }
            } else {
                $videoCompleted = true;
            }
        }

        // if quiz completion enabled then execute this code
        if ($course->quiz_completion == 1) {
            $totalQuiz = $lesson->content()->where('type', 'quiz')->count();
            $quizScore = QuizScore::select('score')->where('course_id', $courseId)->where('lesson_id', $lessonId)->where('user_id', Auth::guard('web')->user()->id)->first();

            if ($totalQuiz > 0) {
                if (!empty($quizScore) && $quizScore->score >= $course->min_quiz_score) {
                    $quizCompleted = true;
                }
            } else {
                $quizCompleted = true;
            }

            if ($content->type == 'quiz' && $quizCompleted == true) {
                $lccCount2 = LessonContentComplete::where('user_id', Auth::guard('web')->id())->where('lesson_id', $content->lesson_id)->where('lesson_content_id', $id)->where('type', 'quiz')->count();
                if ($lccCount2 == 0) {
                    $lcc = new LessonContentComplete();
                    $lcc->user_id = Auth::guard('web')->id();
                    $lcc->lesson_id = $content->lesson_id;
                    $lcc->lesson_content_id = $id;
                    $lcc->type = 'quiz';
                    $lcc->save();
                }
            }
        }

        if (($course->video_watching == 1 && $course->quiz_completion == 0) && $videoCompleted == true) {
            // only video watching enabled, and watched all the videos
            $lessonCompleted = true;
        } else if (($course->video_watching == 0 && $course->quiz_completion == 1) && $quizCompleted == true) {
            // only quiz completion enabled, and passed the quizzes
            $lessonCompleted = true;
        } else if (($course->video_watching == 1 && $course->quiz_completion == 1) && ($videoCompleted == true && $quizCompleted == true)) {
            // both video watching & quiz completion enabled, and both is completed
            $lessonCompleted = true;
        } else if ($course->video_watching == 0 && $course->quiz_completion == 0) {
            // both video watching & quiz completion disabled
            $lessonCompleted = true;
        }

        if ($lessonCompleted == true) {
            $lcCount = LessonComplete::where('user_id', Auth::guard('web')->id())->where('lesson_id', $lessonId)->count();
            if ($lcCount == 0) {
                $lc = new LessonComplete();
                $lc->user_id = Auth::guard('web')->id();
                $lc->lesson_id = $lessonId;
                $lc->save();
            }
        }

        return response()->json(['status' => 'Success', 'lessonCompleted' => $lessonCompleted, 'videoCompleted' => $videoCompleted], 200);
    }

    public function getCertificate($id)
    {
        $courseCompleted = false;

        $language = $this->getLanguage();

        $course = Course::findOrFail($id);
        $courseInfo = $course->information()->where('language_id', $language->id)->first();
        $modules = $courseInfo->module()->where('status', 'published')->orderBy('serial_number', 'asc')->get();

        foreach ($modules as $module) {
            $lessons = $module->lesson()->where('status', 'published')->orderBy('serial_number', 'asc')->get();

            foreach ($lessons as $lesson) {
                if ($lesson->lesson_complete()->where('user_id', Auth::guard('web')->user()->id)->count() > 0) {
                    $courseCompleted = true;
                } else {
                    //          dd($lesson->id);
                    $courseCompleted = false;
                    break 2;
                }
            }
        }

        if ($courseCompleted == true) {
            $queryResult['certificateTitle'] = $course->certificate_title;
            $certificateText = $course->certificate_text;

            // get student name
            $authUser = Auth::guard('web')->user();
            $studentName = $authUser->first_name . ' ' . $authUser->last_name;

            // get course duration
            $duration = Carbon::parse($course->duration);
            $courseDuration = $duration->format('h') . ' hours';

            // get course title
            $courseTitle = $courseInfo->title;

            // get course completion date
            $date = Carbon::now();
            $completionDate = date_format($date, 'F d, Y');

            $certificateText = str_replace('{name}', $studentName, $certificateText);
            $certificateText = str_replace('{duration}', $courseDuration, $certificateText);
            $certificateText = str_replace('{title}', $courseTitle, $certificateText);
            $certificateText = str_replace('{date}', $completionDate, $certificateText);

            $queryResult['certificateText'] = $certificateText;

            $queryResult['instructorInfo'] = $courseInfo->instructor()->where('language_id', $language->id)->select('name', 'occupation')->first();

            return view('frontend.user.course-certificate', $queryResult);
        } else {
            return redirect()->back()->with('warning', 'يجب عليك إكمال هذه الدورة للحصول على الشهادة.');
        }
    }

    public function editProfile()
    {
        $queryResult['breadcrumbImg'] = 'courses.png';

        $queryResult['authUser'] = Auth::guard('web')->user();

        return view('frontend.user.edit-profile', $queryResult);
    }

    public function updateProfile(UserProfileRequest $request)
    {
        $authUser = Auth::guard('web')->user();

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update('./img/users/', $request->file('image'), $authUser->image);
        }

        $authUser->update($request->except('image', 'edit_profile_status') + [
                'image' => $request->exists('image') ? $imageName : $authUser->image,
                'edit_profile_status' => 1
            ]);

        $request->session()->flash('success', 'تم تحديث ملفك الشخصي بنجاح.');

        return redirect()->back();
    }

    public function changePassword()
    {
        $breadcrumbImg = 'courses.png';

        return view('frontend.user.change-password', compact('breadcrumbImg'));
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('user')
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'عملية التحقق من كلمةالمرور لم تتم ',
            'new_password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::guard('web')->user();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', 'تم تحديث كلمة المرور بنجاح.');

        return redirect()->back();
    }

    public function logoutSubmit()
    {
        Auth::guard('web')->logout();

        return redirect()->route('user.login');
    }
}
