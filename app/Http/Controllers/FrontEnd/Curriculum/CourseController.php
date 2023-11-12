<?php

namespace App\Http\Controllers\FrontEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Coupon;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseCategory;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseInformation;
use App\Models\Curriculum\CourseReview;
use App\Models\Language;
use App\Models\PaymentMethods;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function courses(Request $request): View
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_courses', 'meta_description_courses')->first();

        $queryResult['pageHeading'] = "الدورات";
        $queryResult['breadcrumbImg'] = "courses.png";

        $type = $category = $min = $max = $keyword = $sort = null;

        if ($request->filled('type')) {
            $type = $request['type'];
        }
        if ($request->filled('category')) $category = CourseCategory::where('slug', $request['category'])->first()->id;
        if ($request->filled('min') && $request->filled('max')) {
            $min = $request['min'];
            $max = $request['max'];
        }
        if ($request->filled('keyword')) {
            $keyword = $request['keyword'];
        }
        if ($request->filled('sort')) {
            $sort = $request['sort'];
        }

        $courses = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
            ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
            ->join('instructors', 'instructors.id', '=', 'course_informations.instructor_id')
            ->where('courses.status', '=', 'published')
            ->where('course_informations.language_id', '=', $language->id)
            ->when($type, function ($query, $type) {
                if ($type == 'free') {
                    return $query->where('courses.pricing_type', '=', 'free');
                } else if ($type == 'premium') {
                    return $query->where('courses.pricing_type', '=', 'premium');
                }
                return $query;
            })
            ->when($category, function ($query, $category) {
                return $query->where('course_informations.course_category_id', $category);
            })
            ->when(($min && $max), function ($query) use ($min, $max) {
                return $query->where('courses.current_price', '>=', $min)->where('courses.current_price', '<=', $max);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('course_informations.title', 'like', '%' . $keyword . '%');
            })
            ->select('courses.id', 'courses.thumbnail_image', 'courses.pricing_type', 'courses.previous_price', 'courses.current_price', 'courses.average_rating', 'courses.duration', 'course_informations.title', 'course_informations.slug', 'course_categories.name as categoryName', 'course_categories.slug as categorySlug', 'instructors.image as instructorImage', 'instructors.name as instructorName')
            ->when($sort, function ($query, $sort) {
                if ($sort == 'new') {
                    return $query->orderBy('courses.created_at', 'desc');
                } else if ($sort == 'old') {
                    return $query->orderBy('courses.created_at', 'asc');
                } elseif ($sort == 'ascending') {
                    return $query->orderBy('courses.current_price', 'asc');
                } elseif ($sort == 'descending') {
                    return $query->orderBy('courses.current_price', 'desc');
                }

                return $query;
            }, function ($query) {
                return $query->orderByDesc('courses.id');
            })
            ->paginate(9);

        $courses->map(function ($course) {
            $course['enrolmentCount'] = CourseEnrolment::query()->where('course_id', '=', $course->id)
                ->where('payment_status', 'completed')
                ->count();
        });


        $queryResult['courses'] = $courses;

        $queryResult['currencyInfo'] = $this->getCurrencyInfo();

        $queryResult['categories'] = $language->courseCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $queryResult['minPrice'] = Course::where('pricing_type', "=", 'premium')->where('status', 'published')->min('current_price');
        $queryResult['maxPrice'] = Course::where('pricing_type', "=", 'premium')->where('status', 'published')->max('current_price');

        return view('frontend.curriculum.courses', $queryResult);
    }

    public function details($slug)
    {
        $language = $this->getLanguage();

        $queryResult['pageHeading'] = "تفاصيل الدورة";

        $queryResult['bgImg'] = $this->getBreadcrumb();

        $courseId = CourseInformation::where('slug', "=", $slug)->firstOrFail()->course_id;
        $details = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
            ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
            ->join('instructors', 'instructors.id', '=', 'course_informations.instructor_id')
            ->where('courses.status', '=', 'published')
            ->where('course_informations.language_id', '=', $language->id)
            ->where('course_informations.course_id', '=', $courseId)
            ->select('courses.*', 'course_informations.id as courseInfoId', 'course_informations.language_id', 'course_informations.title', 'course_informations.features', 'course_informations.description', 'course_informations.meta_keywords', 'course_informations.meta_description', 'course_categories.name as categoryName', 'instructors.id as instructorId', 'instructors.image as instructorImage', 'instructors.name as instructorName', 'instructors.occupation as instructorJob', 'instructors.description as instructorDetails')
            ->first();

        if (empty($details)) {
            $deLang = Language::where('is_default', 1)->first();
            session()->put('currentLocaleCode', $deLang->code);
            app()->setLocale($deLang->code);
            $details = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
                ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
                ->join('instructors', 'instructors.id', '=', 'course_informations.instructor_id')
                ->where('courses.status', '=', 'published')
                ->where('course_informations.language_id', '=', $deLang->id)
                ->where('course_informations.slug', '=', $slug)
                ->select('courses.*', 'course_informations.id as courseInfoId', 'course_informations.language_id', 'course_informations.title', 'course_informations.features', 'course_informations.description', 'course_informations.meta_keywords', 'course_informations.meta_description', 'course_categories.name as categoryName', 'instructors.id as instructorId', 'instructors.image as instructorImage', 'instructors.name as instructorName', 'instructors.occupation as instructorJob', 'instructors.description as instructorDetails')
                ->firstOrFail();
        }

        $queryResult['details'] = $details;

        $queryResult['currencyInfo'] = $this->getCurrencyInfo();


        $queryResult['paymentMethods'] = PaymentMethods::where('status', 1)->get();

        $categoryId = CourseInformation::where('language_id', "=", $language->id)->where('slug', $slug)->pluck('course_category_id')->first();

        $relatedCourses = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
            ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
            ->join('instructors', 'instructors.id', '=', 'course_informations.instructor_id')
            ->where('courses.status', '=', 'published')
            ->where('course_informations.language_id', '=', $language->id)
            ->where('course_informations.course_category_id', '=', $categoryId)
            ->where('course_informations.slug', '<>', $slug)
            ->select('courses.id', 'courses.thumbnail_image', 'courses.pricing_type', 'courses.previous_price', 'courses.current_price', 'courses.average_rating', 'courses.duration', 'course_informations.title', 'course_informations.slug', 'course_categories.name as categoryName', 'instructors.image as instructorImage', 'instructors.name as instructorName')
            ->orderByDesc('courses.id')
            ->limit(2)
            ->get();

        $relatedCourses->map(function ($relatedCourse) {
            $relatedCourse['enrolmentCount'] = CourseEnrolment::query()->where('course_id', '=', $relatedCourse->id)
                ->where('payment_status', 'completed')
                ->count();
        });

        $queryResult['relatedCourses'] = $relatedCourses;

        $course = $queryResult['details'];

        $queryResult['reviews'] = CourseReview::where('course_id', $course->id)->orderByDesc('id')->get();

        if (Auth::guard('web')->check()) {
            $authUser = Auth::guard('web')->user();
            $enrollment = CourseEnrolment::where('user_id', "=", $authUser->id)->where('course_id', $course->id)->get();
            $queryResult["isPurchase"] = $enrollment->count() > 0 && $enrollment->first()->payment_status == 'completed';
        }


        $queryResult['ratingCount'] = CourseReview::where('course_id', $course->id)->count();

        return view('frontend.curriculum.course-details', $queryResult);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        try {
            $coupon = Coupon::where('code', $request->coupon)->firstOrFail();

            $startDate = Carbon::parse($coupon->start_date);
            $endDate = Carbon::parse($coupon->end_date);
            $todayDate = Carbon::now();

            $courses = $coupon->courses;
            $courses = json_decode($courses, true);
            $courses = !empty($courses) ? $courses : [];

            if (!in_array($request->id, $courses)) {
                return response()->json([
                    'error' => 'قسيمة الخصم غير صالح لهذه الدورة!'
                ]);
            }

            // first, check coupon is valid or not
            if (!$todayDate->between($startDate, $endDate)) {
                return response()->json(['error' => 'قسيمة الخصم غير فعال!']);
            } else {
                $course = Course::findOrFail($request->id);
                $coursePrice = floatval($course->current_price);

                if ($coupon->type == 'fixed') {
                    $reducedPrice = $coursePrice - floatval($coupon->value);

                    $request->session()->put('discountedCourse', $course->id);
                    $request->session()->put('discount', $coupon->value);
                    $request->session()->put('discountedPrice', $reducedPrice);

                    return response()->json([
                        'success' => 'Coupon applied successfully.',
                        'amount' => $coupon->value,
                        'newPrice' => $reducedPrice
                    ]);
                } else {
                    $couponAmount = $coursePrice * ($coupon->value / 100);
                    $couponAmount = round($couponAmount, 2);
                    $reducedPrice = $coursePrice - $couponAmount;

                    $request->session()->put('discountedCourse', $course->id);
                    $request->session()->put('discount', $couponAmount);
                    $request->session()->put('discountedPrice', $reducedPrice);

                    return response()->json([
                        'success' => 'Coupon applied successfully.',
                        'amount' => $couponAmount,
                        'newPrice' => $reducedPrice
                    ]);
                }
            }
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Coupon does not exist!']);
        }
    }

    public function storeFeedback(Request $request, $id)
    {
        $rule = ['rating' => 'required'];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'حقل التقييم للدورة مطلوب')->withInput();
        }

        $flag = 0;

        // get the authenticate user
        $user = Auth::guard('web')->user();

        // then, get the course enrolments of that user
        $enrolments = $user->courseEnrol()->where('payment_status', 'completed')->get();

        if (count($enrolments) > 0) {
            foreach ($enrolments as $enrolment) {
                // check whether selected course has enrolled to this user or not
                if ($enrolment->course_id == $id) {
                    $flag = 1;
                    break;
                }
            }

            if ($flag == 1) {
                CourseReview::updateOrCreate(
                    ['user_id' => $user->id, 'course_id' => $id],
                    ['comment' => $request->comment, 'rating' => $request->rating]
                );

                // now, get the average rating of this course
                $reviews = CourseReview::where('course_id', $id)->get();

                $totalRating = 0;

                foreach ($reviews as $review) {
                    $totalRating += $review->rating;
                }

                $averageRating = $totalRating / $reviews->count();

                // finally, store the average rating of this course
                Course::find($id)->update(['average_rating' => $averageRating]);

                $request->session()->flash('success', 'تم إضافة تقييمك للدورة.');
            } else {
                $request->session()->flash('error', 'لم تلتحق في هذه الدورة من قبل!');
            }
        } else {
            return redirect()->back()->with('error', 'لم تلتحق في اي دورة من الدورات');
        }

        return redirect()->back();
    }
}
