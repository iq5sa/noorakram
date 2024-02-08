<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseEnrolment;
use App\Models\HomePage\Section;

class HomeController extends Controller
{
    public function index()
    {

        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();

        // get the sections of selected home version
        $sectionInfo = Section::first();
        $queryResult['secInfo'] = $sectionInfo;

        $queryResult['heroInfo'] = $language->heroSec()->first();

        $queryResult['secTitleInfo'] = $language->sectionTitle()->first();

        if ($sectionInfo->course_categories_section_status == 1) {
            $queryResult['courseCategoryData'] = Basic::select('course_categories_section_image')->first();
        }

        $categories = $language->courseCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();


        $queryResult['categories'] = $categories;


        if ($sectionInfo->featured_courses_section_status == 1) {
            $courses = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
                ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
                ->join('instructors', 'instructors.id', '=', 'course_informations.instructor_id')
                ->where('courses.status', '=', 'published')
                ->where('courses.is_featured', '=', 'yes')
                ->where('course_informations.language_id', '=', $language->id)
                ->select('courses.id', 'courses.thumbnail_image', 'courses.pricing_type', 'courses.previous_price', 'courses.current_price', 'courses.average_rating', 'courses.duration', 'course_informations.title', 'course_informations.slug', 'course_categories.name as categoryName', 'course_categories.slug as categorySlug', 'instructors.image as instructorImage', 'instructors.name as instructorName')
                ->orderByDesc('courses.id')
                ->get();

            $courses->map(function ($course) {
                $course['enrolmentCount'] = CourseEnrolment::query()->where('course_id', '=', $course->id)
                    ->where('payment_status', 'completed')
                    ->count();
            });

            $queryResult['courses'] = $courses;
        }

        $queryResult['currencyInfo'] = $this->getCurrencyInfo();

        $queryResult['featureData'] = Basic::select('features_section_image')->first();

        $queryResult['features'] = $language->feature()->orderBy('serial_number', 'asc')->get();


        $queryResult['testimonialData'] = Basic::select('testimonials_section_image')->first();

        $queryResult['testimonials'] = $language->testimonial()->orderBy('serial_number', 'asc')->get();

        $queryResult['aboutUsInfo'] = $language->aboutUsSec()->first();

        return view('frontend.home', $queryResult);
    }


    public function aboutPage()
    {
        $queryResult['pageHeading'] = "من نحن";

        $queryResult['breadcrumbImg'] = "about.png";
        $queryResult['bgImg'] = "about.png";

        return view('frontend.about', $queryResult);
    }

    public function termsPage()
    {
        $language = $this->getLanguage();

        $queryResult['pageHeading'] = "البنود والأحكام";
        $queryResult['breadcrumbImg'] = "terms.png";

        $queryResult['bgImg'] = $this->getBreadcrumb();

        return view('frontend.terms', $queryResult);
    }

    public function privacy()
    {
        $language = $this->getLanguage();

        $queryResult['pageHeading'] = "سياسة الخصوصية";
        $queryResult['breadcrumbImg'] = "terms.png";

        $queryResult['bgImg'] = $this->getBreadcrumb();

        return view('frontend.privacy-policy', $queryResult);
    }
}
