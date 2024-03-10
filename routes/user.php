<?php

use App\Http\Controllers\FrontEnd\FacebookLogin;
use App\Http\Controllers\FrontEnd\GoogleLogin;
use App\Http\Controllers\FrontEnd\UserController;
use Illuminate\Support\Facades\Route;

//Auth
Route::middleware(['auth:web', 'account.status'])->group(function () {
    // user redirect to dashboard route
    Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');

    // all enrolment courses route
    Route::get('/my-courses', 'FrontEnd\UserController@myCourses')->name('user.my_courses');

    // download lesson file route
    Route::post('/my-course/curriculum/{id}/download-file', 'FrontEnd\UserController@downloadFile')->name('user.my_course.curriculum.download_file');

    // check quiz's answer route
    Route::get('/my-course/curriculum/check-answer', 'FrontEnd\UserController@checkAns')->name('user.my_course.curriculum.check_ans');

    // store quiz's score route
    Route::post('/my-course/curriculum/store-quiz-score', 'FrontEnd\UserController@storeQuizScore')->name('user.my_course.curriculum.store_quiz_score');

    // lesson-content completion route
    Route::post('/my-course/curriculum/content-completion', 'FrontEnd\UserController@contentCompletion')->name('user.my_course.curriculum.content_completion');

    // get course certificate route
    Route::get('/my-course/{id}/get-certificate', 'FrontEnd\UserController@getCertificate')->name('user.my_course.get_certificate')->middleware('certificate.status');

    // purchase history route
    Route::get('/purchase-history', 'FrontEnd\UserController@purchaseHistory')->name('user.purchase_history');

    // edit profile route
    Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');

    // update profile route
    Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile');

    // change password route
    Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');

    // update password route
    Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password');

    // user logout attempt route
    Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout');
});

//Guest
Route::middleware(['guest:web'])->group(function () {
    // user redirect to login page route
    Route::get('/login', 'FrontEnd\UserController@login')->name('user.login');

    //google
    Route::get('/login/google/auth', [GoogleLogin::class, 'redirect'])->name('user.login.google');
    Route::get('/login/google/callback', [GoogleLogin::class, 'redirectCallback'])->name('user.login.google.callback');

    //facebook
    Route::get('/login/facebook/auth', [FacebookLogin::class, 'redirect'])->name('user.login.facebook');
    Route::get('/login/facebook/callback', [FacebookLogin::class, 'redirectCallback'])->name('user.login.facebook.callback');

    // user login submit route
    Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit');

    // user forget password route
    Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');

    // send mail to user for forget password route
    Route::post('/send-forget-password-mail', 'FrontEnd\UserController@sendMail')->name('user.send_forget_password_mail');

    // reset password route
    Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');

    // user reset password submit route
    Route::post('/reset-password-submit', [UserController::class, 'resetPasswordSubmit'])->name('user.reset_password_submit');

    // user redirect to signup page route
    Route::get('/signup', [UserController::class, 'signup'])->name('user.signup');

    // user signup submit route
    Route::post('/signup-submit', [UserController::class, 'signupSubmit'])->name('user.signup_submit');

    // signup verify route
    Route::get('/signup-verify/{token}', [UserController::class, 'signupVerify']);
});

//Account status
Route::middleware(['account.status'])->group(function () {
    // course curriculum route
    Route::get('/my-course/{id}/curriculum', 'FrontEnd\UserController@curriculum')->name('user.my_course.curriculum');
});
