<?php
/*
 * Copyright (c) 2024 Noorakram.
 * Developed By: Jodx.
 * Contact: in@jodx.dev
 */

use App\Http\Controllers\FrontEnd\Curriculum\Enrol\EnrolmentController;
use App\Http\Controllers\FrontEnd\FacebookLogin;
use App\Http\Controllers\FrontEnd\GoogleLogin;
use App\Http\Controllers\FrontEnd\Payment\AjaxController;
use App\Http\Controllers\FrontEnd\Payment\CheckoutController;
use App\Http\Controllers\FrontEnd\PaymentGateway\TabadulPayment;
use App\Http\Controllers\FrontEnd\PaymentGateway\ZainCashController;
use Illuminate\Support\Facades\Route;
use PHPMailer\PHPMailer\PHPMailer;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');

Route::get('/lang/{code}', 'Controller@changeLanguage');

Route::post('/store-subscriber', 'Controller@storeSubscriber')->name('store_subscriber');

Route::get('/', 'FrontEnd\HomeController@index')->name('index');
Route::get('/privacy-policy', 'FrontEnd\HomeController@privacy')->name('privacy.policy');

Route::get('/onsite-courses', 'FrontEnd\Curriculum\OnsiteCoursesController@index')->name('onsite.courses.index');
Route::get('/courses', 'FrontEnd\Curriculum\CourseController@courses')->name('courses');

Route::get('/course/{slug}', 'FrontEnd\Curriculum\CourseController@details')->name('course_details');

//user enrolment routes
Route::post('/course-enrolment/apply-coupon', 'FrontEnd\Curriculum\CourseController@applyCoupon');
Route::post('/course-enrolment/{id}', [EnrolmentController::class, 'enrol'])->name('course.enrol');
Route::get('/course-enrolment/{order_id}/complete', [EnrolmentController::class, 'complete'])->name('course.enrol.complete');
Route::get('/payment/checkout/{order_id}', [CheckoutController::class, 'index'])->name('payment.checkout');
Route::post('/payment/checkout/{order_id}', [CheckoutController::class, 'submit'])->name('payment.checkout.submit');

Route::get('/payment/zain-cash/redirect', [ZainCashController::class, "redirect"])->name('zainCash.payment.redirect');
Route::get('/payment/tabadul/redirect', [TabadulPayment::class, "redirect"])->name('credit.payment.redirect');
Route::get("payment/success/{order_id}", [CheckoutController::class, "success"])->name("payment.success");
Route::get("payment/failed", [CheckoutController::class, "failed"])->name("payment.failed");

Route::post('/ajax/payment/checkout', [AjaxController::class, 'checkout']);

Route::post('/course/{id}/store-feedback', 'FrontEnd\Curriculum\CourseController@storeFeedback')->name('course.store_feedback');

Route::get('/instructors', 'FrontEnd\InstructorController@instructors')->name('instructors');

Route::get('/blog', 'FrontEnd\BlogController@blogs')->name('blogs');

Route::get('/blog/{slug}', 'FrontEnd\BlogController@details')->name('blog_details');

Route::get('/faq', 'FrontEnd\FaqController@faqs')->name('faqs');

Route::get('/contact', 'FrontEnd\ContactController@contact')->name('contact');
Route::get('/store', "FrontEnd\StoreController@index")->name('store.index');

Route::post('/contact/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail');


Route::post('/advertisement/{id}/total-view', 'Controller@countAdView');

Route::prefix('/user')->middleware(['guest:web'])->group(function () {
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
  Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit');

  // user redirect to signup page route
  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');

  // user signup submit route
  Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit');

  // signup verify route
  Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify');
});

Route::prefix('/user')->middleware(['account.status'])->group(function () {
  // course curriculum route
  Route::get('/my-course/{id}/curriculum', 'FrontEnd\UserController@curriculum')->name('user.my_course.curriculum');
});

Route::prefix('/user')->middleware(['auth:web', 'account.status'])->group(function () {
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

// service unavailable route
Route::get('/service-unavailable', 'Controller@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/admin')->middleware('guest:admin')->group(function () {
  // admin redirect to login page route
  Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');

  // admin login attempt route
  Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');

  // admin forget password route
  Route::get('/forget-password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');

  // send mail to admin for forget password route
  Route::post('/mail-for-forget-password', 'BackEnd\AdminController@sendMail')->name('admin.mail_for_forget_password');
});

Route::prefix('/admin')->middleware('auth:admin')->group(function () {
  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');

  // admin profile settings route start
  Route::get('/edit-profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update-profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change-password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update-password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end

  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');


  // admin management route start
  Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
    Route::get('/role-permissions', 'BackEnd\Administrator\RolePermissionController@index')->name('admin.admin_management.role_permissions');

    Route::post('/store-role', 'BackEnd\Administrator\RolePermissionController@store')->name('admin.admin_management.store_role');

    Route::get('/role/{id}/permissions', 'BackEnd\Administrator\RolePermissionController@permissions')->name('admin.admin_management.role.permissions');

    Route::post('/role/{id}/update-permissions', 'BackEnd\Administrator\RolePermissionController@updatePermissions')->name('admin.admin_management.role.update_permissions');

    Route::post('/update-role', 'BackEnd\Administrator\RolePermissionController@update')->name('admin.admin_management.update_role');

    Route::post('/delete-role/{id}', 'BackEnd\Administrator\RolePermissionController@destroy')->name('admin.admin_management.delete_role');

    Route::get('/registered-admins', 'BackEnd\Administrator\SiteAdminController@index')->name('admin.admin_management.registered_admins');

    Route::post('/store-admin', 'BackEnd\Administrator\SiteAdminController@store')->name('admin.admin_management.store_admin');

    Route::post('/admin/{id}/update-status', 'BackEnd\Administrator\SiteAdminController@updateStatus')->name('admin.admin_management.admin.update_status');

    Route::post('/update-admin', 'BackEnd\Administrator\SiteAdminController@update')->name('admin.admin_management.update_admin');

    Route::post('/delete-admin/{id}', 'BackEnd\Administrator\SiteAdminController@destroy')->name('admin.admin_management.delete_admin');
  });
  // admin management route end


  // language management route start
  Route::prefix('/language-management')->middleware('permission:Language Management')->group(function () {
    Route::get('', 'BackEnd\LanguageController@index')->name('admin.language_management');

    Route::post('/store-language', 'BackEnd\LanguageController@store')->name('admin.language_management.store_language');

    Route::post('/{id}/make-default-language', 'BackEnd\LanguageController@makeDefault')->name('admin.language_management.make_default_language');

    Route::post('/update-language', 'BackEnd\LanguageController@update')->name('admin.language_management.update_language');

    Route::get('/{id}/edit-keyword', 'BackEnd\LanguageController@editKeyword')->name('admin.language_management.edit_keyword');

    Route::post('/{id}/update-keyword', 'BackEnd\LanguageController@updateKeyword')->name('admin.language_management.update_keyword');

    Route::post('/{id}/delete-language', 'BackEnd\LanguageController@destroy')->name('admin.language_management.delete_language');

    Route::get('/{id}/check-rtl', 'BackEnd\LanguageController@checkRTL');
  });
  // language management route end


  Route::prefix('/basic-settings')->middleware('permission:Basic Settings')->group(function () {
    // basic settings maintenance-mode route
    Route::get('/maintenance-mode', 'BackEnd\BasicSettings\BasicController@maintenance')->name('admin.basic_settings.maintenance_mode');

    Route::post('/update-maintenance-mode', 'BackEnd\BasicSettings\BasicController@updateMaintenance')->name('admin.basic_settings.update_maintenance_mode');

    // basic settings cookie-alert route
    Route::get('/cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/update-cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic settings footer-logo route
    Route::get('/footer-logo', 'BackEnd\BasicSettings\BasicController@footerLogo')->name('admin.basic_settings.footer_logo');

    Route::post('/update-footer-logo', 'BackEnd\BasicSettings\BasicController@updateFooterLogo')->name('admin.basic_settings.update_footer_logo');

    // basic-settings social-media route
    Route::get('/social-medias', 'BackEnd\BasicSettings\SocialMediaController@index')->name('admin.basic_settings.social_medias');

    Route::post('/store-social-media', 'BackEnd\BasicSettings\SocialMediaController@store')->name('admin.basic_settings.store_social_media');

    Route::put('/update-social-media', 'BackEnd\BasicSettings\SocialMediaController@update')->name('admin.basic_settings.update_social_media');

    Route::post('/delete-social-media/{id}', 'BackEnd\BasicSettings\SocialMediaController@destroy')->name('admin.basic_settings.delete_social_media');
  });


  // announcement-popup route start
  Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
    Route::get('', 'BackEnd\PopupController@index')->name('admin.announcement_popups');

    Route::get('/select-popup-type', 'BackEnd\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');

    Route::get('/create-popup/{type}', 'BackEnd\PopupController@create')->name('admin.announcement_popups.create_popup');

    Route::post('/store-popup', 'BackEnd\PopupController@store')->name('admin.announcement_popups.store_popup');

    Route::post('/popup/{id}/update-status', 'BackEnd\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');

    Route::get('/edit-popup/{id}', 'BackEnd\PopupController@edit')->name('admin.announcement_popups.edit_popup');

    Route::post('/update-popup/{id}', 'BackEnd\PopupController@update')->name('admin.announcement_popups.update_popup');

    Route::post('/delete-popup/{id}', 'BackEnd\PopupController@destroy')->name('admin.announcement_popups.delete_popup');

    Route::post('/bulk-delete-popup', 'BackEnd\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
  });
  // announcement-popup route end


  // menu-builder route start
  Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
    Route::get('', 'BackEnd\MenuBuilderController@index')->name('admin.menu_builder');

    Route::post('/update-menus', 'BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
  });
  // menu-builder route end


  // course route start
  Route::prefix('/course-management')->middleware('permission:Course Management')->group(function () {

    Route::get('/categories', 'BackEnd\Curriculum\CategoryController@index')->name('admin.course_management.categories');

    Route::post('/store-category', 'BackEnd\Curriculum\CategoryController@store')->name('admin.course_management.store_category');

    Route::post('/category/{id}/update-featured', 'BackEnd\Curriculum\CategoryController@updateFeatured')->name('admin.course_management.category.update_featured');

    Route::put('/update-category', 'BackEnd\Curriculum\CategoryController@update')->name('admin.course_management.update_category');

    Route::post('/delete-category/{id}', 'BackEnd\Curriculum\CategoryController@destroy')->name('admin.course_management.delete_category');

    Route::post('/bulk-delete-category', 'BackEnd\Curriculum\CategoryController@bulkDestroy')->name('admin.course_management.bulk_delete_category');

    Route::get('/courses', 'BackEnd\Curriculum\CourseController@index')->name('admin.course_management.courses');

    Route::get('/create-course', 'BackEnd\Curriculum\CourseController@create')->name('admin.course_management.create_course');

    Route::post('/store-course', 'BackEnd\Curriculum\CourseController@store')->name('admin.course_management.store_course');

    Route::post('/course/{id}/update-status', 'BackEnd\Curriculum\CourseController@updateStatus')->name('admin.course_management.course.update_status');

    Route::post('/course/{id}/update-featured', 'BackEnd\Curriculum\CourseController@updateFeatured')->name('admin.course_management.course.update_featured');

    Route::get('/edit-course/{id}', 'BackEnd\Curriculum\CourseController@edit')->name('admin.course_management.edit_course');

    Route::post('/update-course/{id}', 'BackEnd\Curriculum\CourseController@update')->name('admin.course_management.update_course');

    Route::prefix('/course')->group(function () {
      Route::get('/{id}/modules', 'BackEnd\Curriculum\ModuleController@index')->name('admin.course_management.course.modules');

      Route::post('/{id}/store-module', 'BackEnd\Curriculum\ModuleController@store')->name('admin.course_management.course.store_module');

      Route::post('/update-module', 'BackEnd\Curriculum\ModuleController@update')->name('admin.course_management.course.update_module');

      Route::post('/delete-module/{id}', 'BackEnd\Curriculum\ModuleController@destroy')->name('admin.course_management.course.delete_module');

      Route::post('/bulk-delete-module', 'BackEnd\Curriculum\ModuleController@bulkDestroy')->name('admin.course_management.course.bulk_delete_module');
    });

    Route::prefix('/module')->group(function () {
      Route::post('/{id}/store-lesson', 'BackEnd\Curriculum\LessonController@store')->name('admin.course_management.module.store_lesson');

      Route::post('/update-lesson', 'BackEnd\Curriculum\LessonController@update')->name('admin.course_management.module.update_lesson');
    });

    Route::prefix('/lesson')->group(function () {
      Route::get('/{id}/contents', 'BackEnd\Curriculum\LessonContentController@contents')->name('admin.course_management.lesson.contents');

      Route::post('/upload-video', 'BackEnd\Curriculum\LessonContentController@uploadVideo')->name('admin.course_management.lesson.upload_video');

      Route::post('/remove-video', 'BackEnd\Curriculum\LessonContentController@removeVideo')->name('admin.course_management.lesson.remove_video');

      Route::post('/{id}/store-video', 'BackEnd\Curriculum\LessonContentController@storeVideo')->name('admin.course_management.lesson.store_video');

      Route::post('/upload-file', 'BackEnd\Curriculum\LessonContentController@uploadFile')->name('admin.course_management.lesson.upload_file');

      Route::post('/remove-file', 'BackEnd\Curriculum\LessonContentController@removeFile')->name('admin.course_management.lesson.remove_file');

      Route::post('/{id}/store-file', 'BackEnd\Curriculum\LessonContentController@storeFile')->name('admin.course_management.lesson.store_file');

      Route::get('/download-file/{id}', 'BackEnd\Curriculum\LessonContentController@downloadFile')->name('admin.course_management.lesson.download_file');

      Route::post('/{id}/store-text', 'BackEnd\Curriculum\LessonContentController@storeText')->name('admin.course_management.lesson.store_text');

      Route::post('/update-text', 'BackEnd\Curriculum\LessonContentController@updateText')->name('admin.course_management.lesson.update_text');

      Route::post('/{id}/store-code', 'BackEnd\Curriculum\LessonContentController@storeCode')->name('admin.course_management.lesson.store_code');

      Route::post('/update-code', 'BackEnd\Curriculum\LessonContentController@updateCode')->name('admin.course_management.lesson.update_code');

      Route::post('/delete-content/{id}', 'BackEnd\Curriculum\LessonContentController@destroyContent')->name('admin.course_management.lesson.delete_content');

      Route::get('/{id}/create-quiz', 'BackEnd\Curriculum\LessonQuizController@create')->name('admin.course_management.lesson.create_quiz');

      Route::post('/{id}/store-quiz', 'BackEnd\Curriculum\LessonQuizController@store')->name('admin.course_management.lesson.store_quiz');

      Route::get('/{id}/manage-quiz', 'BackEnd\Curriculum\LessonQuizController@index')->name('admin.course_management.lesson.manage_quiz');

      Route::get('/{lessonId}/edit-quiz/{quizId}', 'BackEnd\Curriculum\LessonQuizController@edit')->name('admin.course_management.lesson.edit_quiz');

      Route::get('/get-ans/{id}', 'BackEnd\Curriculum\LessonQuizController@getAns')->name('admin.course_management.lesson.get_ans');

      Route::post('/update-quiz/{id}', 'BackEnd\Curriculum\LessonQuizController@update')->name('admin.course_management.lesson.update_quiz');

      Route::post('/delete-quiz/{id}', 'BackEnd\Curriculum\LessonQuizController@destroy')->name('admin.course_management.lesson.delete_quiz');

      Route::post('/sort-contents', 'BackEnd\Curriculum\LessonContentController@sort')->name('admin.course_management.lesson.sort_contents');
    });

    Route::post('/module/delete-lesson/{id}', 'BackEnd\Curriculum\LessonController@destroy')->name('admin.course_management.module.delete_lesson');

    Route::prefix('/course')->group(function () {
      Route::get('/{id}/faqs', 'BackEnd\Curriculum\CourseFaqController@index')->name('admin.course_management.course.faqs');

      Route::post('/{id}/store-faq', 'BackEnd\Curriculum\CourseFaqController@store')->name('admin.course_management.course.store_faq');

      Route::post('/update-faq', 'BackEnd\Curriculum\CourseFaqController@update')->name('admin.course_management.course.update_faq');

      Route::post('/delete-faq/{id}', 'BackEnd\Curriculum\CourseFaqController@destroy')->name('admin.course_management.course.delete_faq');

      Route::post('/bulk-delete-faq', 'BackEnd\Curriculum\CourseFaqController@bulkDestroy')->name('admin.course_management.course.bulk_delete_faq');

      Route::get('/{id}/thanks-page', 'BackEnd\Curriculum\CourseController@thanksPage')->name('admin.course_management.course.thanks_page');

      Route::post('/{id}/update-thanks-page', 'BackEnd\Curriculum\CourseController@updateThanksPage')->name('admin.course_management.course.update_thanks_page');

      Route::get('/{id}/certificate-settings', 'BackEnd\Curriculum\CourseController@certificateSettings')->name('admin.course_management.course.certificate_settings');

      Route::post('/{id}/update-certificate-settings', 'BackEnd\Curriculum\CourseController@updateCertificateSettings')->name('admin.course_management.course.update_certificate_settings');
    });

    Route::post('/delete-course/{id}', 'BackEnd\Curriculum\CourseController@destroy')->name('admin.course_management.delete_course');

    Route::post('/bulk-delete-course', 'BackEnd\Curriculum\CourseController@bulkDestroy')->name('admin.course_management.bulk_delete_course');

    Route::get('/coupons', 'BackEnd\Curriculum\CouponController@index')->name('admin.course_management.coupons');

    Route::post('/store-coupon', 'BackEnd\Curriculum\CouponController@store')->name('admin.course_management.store_coupon');

    Route::post('/update-coupon', 'BackEnd\Curriculum\CouponController@update')->name('admin.course_management.update_coupon');

    Route::post('/delete-coupon/{id}', 'BackEnd\Curriculum\CouponController@destroy')->name('admin.course_management.delete_coupon');

    Route::get('/cashCodes', 'BackEnd\Curriculum\CashCodesController@index')->name('admin.course_management.cashCodes');
    Route::post('/store-code', 'BackEnd\Curriculum\CashCodesController@store')->name('admin.course_management.store_code');
    Route::post('/delete-code/{id}', 'BackEnd\Curriculum\CashCodesController@destroy')->name('admin.course_management.delete_code');

  });
  // course route end


  // Admin course enrolment route start
  Route::middleware('permission:Course Enrolments')->group(function () {
    Route::get('/course-enrolments', 'BackEnd\Curriculum\EnrolmentController@index')->name('admin.course_enrolments');

    Route::prefix('/course-enrolment')->group(function () {
      Route::post('/{id}/update-payment-status', 'BackEnd\Curriculum\EnrolmentController@updatePaymentStatus')->name('admin.course_enrolment.update_payment_status');

      Route::get('/{id}/details', 'BackEnd\Curriculum\EnrolmentController@show')->name('admin.course_enrolment.details');

      Route::post('/{id}/delete', 'BackEnd\Curriculum\EnrolmentController@destroy')->name('admin.course_enrolment.delete');
    });

    Route::get('/course-enrolments/report', 'BackEnd\Curriculum\EnrolmentController@report')->name('admin.course_enrolments.report');

    Route::get('/course-enrolments/export', 'BackEnd\Curriculum\EnrolmentController@export')->name('admin.course_enrolments.export');

    Route::post('/course-enrolments/bulk-delete', 'BackEnd\Curriculum\EnrolmentController@bulkDestroy')->name('admin.course_enrolments.bulk_delete');
  });
  // course enrolment route end

  // blog route start
  Route::prefix('/blog-management')->middleware('permission:Blog Management')->group(function () {
    Route::get('/categories', 'BackEnd\Journal\CategoryController@index')->name('admin.blog_management.categories');

    Route::post('/store-category', 'BackEnd\Journal\CategoryController@store')->name('admin.blog_management.store_category');

    Route::put('/update-category', 'BackEnd\Journal\CategoryController@update')->name('admin.blog_management.update_category');

    Route::post('/delete-category/{id}', 'BackEnd\Journal\CategoryController@destroy')->name('admin.blog_management.delete_category');

    Route::post('/bulk-delete-category', 'BackEnd\Journal\CategoryController@bulkDestroy')->name('admin.blog_management.bulk_delete_category');

    Route::get('/blogs', 'BackEnd\Journal\BlogController@index')->name('admin.blog_management.blogs');

    Route::get('/create-blog', 'BackEnd\Journal\BlogController@create')->name('admin.blog_management.create_blog');

    Route::post('/store-blog', 'BackEnd\Journal\BlogController@store')->name('admin.blog_management.store_blog');

    Route::get('/edit-blog/{id}', 'BackEnd\Journal\BlogController@edit')->name('admin.blog_management.edit_blog');

    Route::post('/update-blog/{id}', 'BackEnd\Journal\BlogController@update')->name('admin.blog_management.update_blog');

    Route::post('/delete-blog/{id}', 'BackEnd\Journal\BlogController@destroy')->name('admin.blog_management.delete_blog');

    Route::post('/bulk-delete-blog', 'BackEnd\Journal\BlogController@bulkDestroy')->name('admin.blog_management.bulk_delete_blog');
  });
  // blog route end


  // faq route start
  Route::prefix('/faq-management')->middleware('permission:FAQ Management')->group(function () {
    Route::get('', 'BackEnd\FaqController@index')->name('admin.faq_management');

    Route::post('/store-faq', 'BackEnd\FaqController@store')->name('admin.faq_management.store_faq');

    Route::post('/update-faq', 'BackEnd\FaqController@update')->name('admin.faq_management.update_faq');

    Route::post('/delete-faq/{id}', 'BackEnd\FaqController@destroy')->name('admin.faq_management.delete_faq');

    Route::post('/bulk-delete-faq', 'BackEnd\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq route end


  // user management route start
  Route::prefix('/user-management')->middleware('permission:User Management')->group(function () {
    Route::get('/registered-users', 'BackEnd\User\UserController@index')->name('admin.user_management.registered_users');

    Route::prefix('/user/{id}')->group(function () {
      Route::post('/update-email-status', 'BackEnd\User\UserController@updateEmailStatus')->name('admin.user_management.user.update_email_status');

      Route::post('/update-account-status', 'BackEnd\User\UserController@updateAccountStatus')->name('admin.user_management.user.update_account_status');

      Route::get('/details', 'BackEnd\User\UserController@show')->name('admin.user_management.user_details');

      Route::get('/change-password', 'BackEnd\User\UserController@changePassword')->name('admin.user_management.user.change_password');

      Route::post('/update-password', 'BackEnd\User\UserController@updatePassword')->name('admin.user_management.user.update_password');

      Route::post('/delete', 'BackEnd\User\UserController@destroy')->name('admin.user_management.user.delete');
    });

    Route::post('/bulk-delete-user', 'BackEnd\User\UserController@bulkDestroy')->name('admin.user_management.bulk_delete_user');

    Route::get('/subscribers', 'BackEnd\User\SubscriberController@index')->name('admin.user_management.subscribers');

    Route::post('/subscriber/{id}/delete', 'BackEnd\User\SubscriberController@destroy')->name('admin.user_management.subscriber.delete');

    Route::post('/bulk-delete-subscriber', 'BackEnd\User\SubscriberController@bulkDestroy')->name('admin.user_management.bulk_delete_subscriber');

    Route::get('/mail-for-subscribers', 'BackEnd\User\SubscriberController@writeEmail')->name('admin.user_management.mail_for_subscribers');

    Route::post('/subscribers/send-email', 'BackEnd\User\SubscriberController@sendEmail')->name('admin.user_management.subscribers.send_email');

    Route::prefix('/push-notification')->group(function () {
      Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

      Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

      Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

      Route::post('/send-notification', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send_notification');
    });
  });
  // user management route end


  // upload image in summernote route
  Route::prefix('/summernote')->group(function () {
    Route::post('/upload-image', 'BackEnd\SummernoteController@upload');

    Route::post('/remove-image', 'BackEnd\SummernoteController@remove');
  });
});

/*
|--------------------------------------------------------------------------
| Custom Page Route For UI
|--------------------------------------------------------------------------
*/

Route::get("/store/books", "FrontEnd\BooksStoreController@index")->name("books");
Route::get("/about", "FrontEnd\HomeController@aboutPage")->name("home.aboutPage");
Route::get("/terms", "FrontEnd\HomeController@termsPage")->name("home.termsPage");
Route::get("/appointment/book", "FrontEnd\AppointmentsController@index")->name("appointment.index");
Route::post("/appointment/book", "FrontEnd\AppointmentsController@store")->name("appointment.book.store");
Route::get("/appointment/book/success", "FrontEnd\AppointmentsController@paymentSuccess");

Route::get("/mail/send", function () {
  require 'vendor/autoload.php';


  $mail = new PHPMailer();
  $mail->Encoding = "base64";
  $mail->SMTPAuth = true;
  $mail->Host = "smtp.zeptomail.com";
  $mail->Port = 587;
  $mail->Username = "emailapikey";
  $mail->Password = 'wSsVR60j/R/5Bvp5nDGsJrowkQtXB1mnERh63VXzv3L6SP7Lp8c8lkTNBw71SqVOFmc8EDQUpul/mRpR0DsNhowrnwpUASiF9mqRe1U4J3x17qnvhDzOX2tZlxePJYgBwg9pnWBiFcFu';
  $mail->SMTPSecure = 'TLS';
  $mail->isSMTP();
});
