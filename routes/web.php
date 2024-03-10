<?php
/*
 * Copyright (c) 2024 Noorakram.
 * Developed By: Jodx.
 * Contact: in@jodx.dev
 */

use App\Http\Controllers\FrontEnd\Curriculum\Enrol\EnrolmentController;
use App\Http\Controllers\FrontEnd\Payment\AjaxController;
use App\Http\Controllers\FrontEnd\Payment\CheckoutController;
use App\Http\Controllers\FrontEnd\PaymentGateway\TabadulPayment;
use App\Http\Controllers\FrontEnd\PaymentGateway\ZainCashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'FrontEnd\HomeController@index')->name('index');
Route::get('/courses', 'FrontEnd\Curriculum\CourseController@courses')->name('courses');
Route::get('/course/{slug}', 'FrontEnd\Curriculum\CourseController@details')->name('course_details');
Route::post('/course/{id}/store-feedback', 'FrontEnd\Curriculum\CourseController@storeFeedback')->name('course.store_feedback');
Route::post('/store-subscriber', 'Controller@storeSubscriber')->name('store_subscriber');
Route::get('/privacy-policy', 'FrontEnd\HomeController@privacy')->name('privacy.policy');
Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');
Route::get('/instructors', 'FrontEnd\InstructorController@instructors')->name('instructors');
Route::get('/blog', 'FrontEnd\BlogController@blogs')->name('blogs');
Route::get('/blog/{slug}', 'FrontEnd\BlogController@details')->name('blog_details');
Route::get('/faq', 'FrontEnd\FaqController@faqs')->name('faqs');
Route::get('/contact', 'FrontEnd\ContactController@contact')->name('contact');
Route::get('/store', "FrontEnd\StoreController@index")->name('store.index');
Route::post('/contact/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail');
Route::post('/advertisement/{id}/total-view', 'Controller@countAdView');


//user enrolment routes
Route::post('/course-enrolment/apply-coupon', 'FrontEnd\Curriculum\CourseController@applyCoupon');
Route::post('/course-enrolment/{id}', [EnrolmentController::class, 'enrol'])->name('course.enrol');
Route::get('/course-enrolment/{order_id}/complete', [EnrolmentController::class, 'complete'])->name('course.enrol.complete');


//Payment routes
Route::get('/payment/checkout/{order_id}', [CheckoutController::class, 'index'])->name('payment.checkout');
Route::post('/payment/checkout/{order_id}', [CheckoutController::class, 'submit'])->name('payment.checkout.submit');
Route::get('/payment/zain-cash/redirect', [ZainCashController::class, 'redirect'])->name('zainCash.payment.redirect');
Route::get('/payment/tabadul/redirect', [TabadulPayment::class, 'redirect'])->name('credit.payment.redirect');
Route::get('payment/success/{order_id}', [CheckoutController::class, 'success'])->name('payment.success');
Route::get('payment/failed', [CheckoutController::class, 'failed'])->name('payment.failed');
Route::post('/ajax/payment/checkout', [AjaxController::class, 'checkout']);

Route::get('/service-unavailable', 'Controller@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');


Route::get('/store/books', "FrontEnd\BooksStoreController@index")->name('books');
Route::get('/about', "FrontEnd\HomeController@aboutPage")->name('home.aboutPage');
Route::get('/terms', "FrontEnd\HomeController@termsPage")->name('home.termsPage');
Route::get('/appointment/book', "FrontEnd\AppointmentsController@index")->name('appointment.index');
Route::post('/appointment/book', "FrontEnd\AppointmentsController@store")->name('appointment.book.store');
Route::get('/appointment/book/success', "FrontEnd\AppointmentsController@paymentSuccess");
