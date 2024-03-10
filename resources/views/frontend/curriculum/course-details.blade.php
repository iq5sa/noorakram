@php use Carbon\Carbon;use Illuminate\Support\Facades\DB; @endphp
@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading }}
    @endif
@endsection

@section('metaKeywords')
    {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
    {{ $details->meta_description }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/css/summernote-content.css') }}">
@endsection

@section('content')
    @php
        if (isset($currencyInfo)){
             $position = $currencyInfo->base_currency_symbol_position;
            $symbol = $currencyInfo->base_currency_symbol;
        }

    @endphp

            <!--====== COURSE TITLE PART START ======-->
    <section class="course-title-area pt-120 pb-120 bg_cover lazy"
             data-bg="{{ asset('/img/courses/covers/' . $details->cover_image) }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="course-title-content">
                        <div class="course-title-content-title">
                            <span>{{ $details->categoryName }}</span>
                            <h2 class="title">{{ $details->title }}</h2>
                        </div>
                        <div class="course-rating d-flex">
                            @if (!is_null($details->average_rating))
                                <div class="rate">
                                    <div class="rating" style="width: {{ $details->average_rating * 20 . '%;' }}"></div>
                                </div>
                                <p>{{ $details->average_rating . ' (' . $ratingCount . ' ' . __('ratings') . ')' }}</p>
                            @endif

                        </div>
                        <div class="course-info">
                            <ul class="mb-3">
                                <li>
                                    <i class="fal fa-user text-white"></i> {{ 'المحاضر: ' . ' ' . $details->instructorName }}

                                </li>

                                @if($details->type ==='onsite')
                                    <li>
                                        <i class="fal fa-map-marker text-white"></i> موقع الحضور:
                                        <a class="text-white btn-link" href="{{ $details->location_url }}">اضغط هنا</a>
                                    </li>
                                @endif
                            </ul>
                            @if($details->type ==='onsite')
                                <ul>
                                    <li>
                                        <i class="fal fa-calendar text-white"></i> تاريخ بدأ الدورة:
                                        {{ Carbon::parse($details->start_at)->format('Y-m-d') }}
                                    </li>
                                    <li>
                                        <i class="fal fa-calendar-day text-white"></i> عدد الايام:
                                        {{ $details->days_count }}
                                    </li>


                                </ul>
                            @endif

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--====== COURSE TITLE PART END ======-->

    <!--====== COURSE DETAILS PART START ======-->
    <section class="course-details-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">


                    <div class="course-details-items white-bg">
                        @if($details->id ==19)

                            <a
                                    href="https://iframe.mediadelivery.net/embed/200921/db53cda9-4efa-4e0a-a22f-adaadc88bf38?autoplay=true&loop=false&muted=false&preload=true&responsive=true"
                                    class="video-popup"> <img
                                        src="{{asset("images/course-pro-banner.jpg")}}"
                                        alt="course-banner" class="img-fluid"></a>
                        @endif
                        <div class="course-thumb">
                            <div class="tab-btns">
                                <ul class="nav nav-pills d-flex justify-content-between" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-1-tab" data-toggle="pill" href="#pills-1"
                                           role="tab"
                                           aria-controls="pills-1" aria-selected="true"><i
                                                    class="fal fa-list"></i> {{ __('Description') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-2-tab" data-toggle="pill" href="#pills-2"
                                           role="tab"
                                           aria-controls="pills-2" aria-selected="false"><i
                                                    class="fal fa-book"></i> {{ __('Curriculum') }}
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-4-tab" data-toggle="pill" href="#pills-4"
                                           role="tab"
                                           aria-controls="pills-4" aria-selected="false"><i
                                                    class="fal fa-stars"></i> {{ __('Reviews') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade show active" id="pills-1" role="tabpanel"
                                 aria-labelledby="pills-1-tab">
                                <div class="course-details-item pt-4">
                                    <div class="summernote-content">
                                        {!! replaceBaseUrl($details->description, 'summernote') !!}
                                    </div>

                                    <div class="course-faq">
                                        <h4 class="title">{{ __('Frequently Asked Questions') }}</h4>
                                    </div>

                                    @php
                                        $faqs = DB::table('course_faqs')->where('course_id', $details->id)->where('language_id', $details->language_id)
                                          ->orderBy('serial_number')
                                          ->get();
                                    @endphp

                                    @if ($faqs->count() == 0)
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="text-center bg-light py-5">{{ __('No FAQ Found') . '!' }}</h5>
                                            </div>
                                        </div>
                                    @else
                                        <div class="course-accordian">
                                            <div class="accordion" id="accordionCourse">
                                                @foreach ($faqs as $faq)
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <a class="{{ $loop->first ? '' : 'collapsed' }} title"
                                                               data-toggle="collapse"
                                                               data-target="{{ '#collapse-' . $faq->id }}"
                                                               aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                                                {{ $faq->question }}
                                                            </a>
                                                        </div>
                                                        <div id="{{ 'collapse-' . $faq->id }}"
                                                             class="collapse {{ $loop->first ? 'show' : '' }}"
                                                             data-parent="#accordionCourse">
                                                            <div class="card-body">
                                                                <p>{{ $faq->answer }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                                <div class="curriculum-accordion">
                                    <div class="accordion" id="curriculumAccordion">
                                        @php
                                            $modules = DB::table('modules')->where('course_information_id', $details->courseInfoId)
                                              ->where('status', 'published')
                                              ->orderBy('serial_number')
                                              ->get();
                                        @endphp

                                        @foreach ($modules as $module)
                                            <div class="card">
                                                @php
                                                    $modulePeriod = $module->duration;
                                                    $array = explode(':', $modulePeriod);
                                                    $moduleHour = $array[0];
                                                    $moduleDuration = Carbon::parse($modulePeriod);
                                                @endphp

                                                <div class="card-header">
                                                    <a class="{{ $loop->first ? '' : 'collapsed' }} title"
                                                       data-toggle="collapse"
                                                       data-target="{{ '#collapse-' . $module->id }}"
                                                       aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                                        {{ $module->title }}
                                                        <span
                                                                class="badge badge-warning">{{ $moduleHour == '00' ? '' : $moduleDuration->format('h') . 'h ' }}{{ $moduleDuration->format('i') . 'm' }}</span>
                                                    </a>
                                                </div>
                                                <div id="{{ __('collapse-') . $module->id }}"
                                                     class="collapse {{ $loop->first ? 'show' : '' }}"
                                                     aria-labelledby="{{ 'heading-' . $module->id }}"
                                                     data-parent="#curriculumAccordion">
                                                    @php
                                                        $lessons = DB::table('lessons')->where('module_id', $module->id)
                                                          ->where('status', 'published')
                                                          ->orderBy('serial_number')
                                                          ->get();
                                                    @endphp

                                                    <div class="card-body">
                                                        <ul class="play-list">
                                                            @foreach ($lessons as $lesson)
                                                                @php
                                                                    $lessonPeriod = $lesson->duration;
                                                                    $lessonDuration = Carbon::parse($lessonPeriod);
                                                                @endphp

                                                                <li>
                                                                    <a><i class="fas fa-play"></i>{{ $lesson->title }}
                                                                        <span
                                                                                class="time">{{ $lessonDuration->format('i') . ':' }}{{ $lessonDuration->format('s') }}</span></a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="pills-4" role="tabpanel" aria-labelledby="pills-4-tab">
                                <div class="reviews-area">
                                    @guest('web')
                                        <div class="text-center">
                                            <p class="mb-3">لا يمكن إضافة ردود قبل تسجيل الدخول</p>
                                            <a href="{{ route('user.login', ['redirectPath' => 'course_details']) }}"
                                               class="btn btn-primary">{{ __('Login') }}</a>
                                        </div>

                                    @endguest

                                    @auth('web')
                                        <div class="rating-form-area">
                                            <h4 class="title">{{ __('Ratings') }}</h4>
                                            <div class="rating-form mb-35">
                                                <form action="{{ route('course.store_feedback', ['id' => $details->id]) }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="form_rating">
                                                        <ul class="rating">
                                                            <li class="review-value review-1">
                                                                <span class="far fa-star" data-ratingVal="1"></span>
                                                            </li>
                                                            <li class="review-value review-2">
                                                                <span class="far fa-star" data-ratingVal="2"></span>
                                                                <span class="far fa-star" data-ratingVal="2"></span>
                                                            </li>
                                                            <li class="review-value review-3">
                                                                <span class="far fa-star" data-ratingVal="3"></span>
                                                                <span class="far fa-star" data-ratingVal="3"></span>
                                                                <span class="far fa-star" data-ratingVal="3"></span>
                                                            </li>
                                                            <li class="review-value review-4">
                                                                <span class="far fa-star" data-ratingVal="4"></span>
                                                                <span class="far fa-star" data-ratingVal="4"></span>
                                                                <span class="far fa-star" data-ratingVal="4"></span>
                                                                <span class="far fa-star" data-ratingVal="4"></span>
                                                            </li>
                                                            <li class="review-value review-5">
                                                                <span class="far fa-star" data-ratingVal="5"></span>
                                                                <span class="far fa-star" data-ratingVal="5"></span>
                                                                <span class="far fa-star" data-ratingVal="5"></span>
                                                                <span class="far fa-star" data-ratingVal="5"></span>
                                                                <span class="far fa-star" data-ratingVal="5"></span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <input type="hidden" id="rating-id" name="rating">

                                                    <div class="form_group">
                                                        <label class="w-100">
                              <textarea class="form-control" name="comment"
                                        placeholder="{{ __('Enter Your Feedback') }}">{{ old('comment') }}</textarea>
                                                        </label>
                                                    </div>

                                                    <div class="form_group">
                                                        <button class="btn btn-primary">{{ __('Submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endauth

                                    @if ($reviews->count() == 0)
                                        <h4 class="mt-25 text-center">{{ __('This course is not reviewed yet') . '.' }}</h4>
                                    @else
                                        <div class="reviews-list">
                                            @foreach ($reviews->take(10) as $review)
                                                <div class="reviews-item">
                                                    @php $user = $review->userInfo()->first(); @endphp

                                                    <div class="thumb">
                                                        @if (is_null($user->image))
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 width="80"
                                                                 height="80" x="0" y="0" viewBox="0 0 512 512"
                                                                 style="enable-background:new 0 0 512 512"
                                                                 xml:space="preserve" class=""><g
                                                                        transform="matrix(0.9999999999999991,0,0,0.9999999999999991,1.9895196601282805e-13,1.9895196601282805e-13)">
                                                                    <rect width="424.8" height="424.8" x="43.6" y="43.6"
                                                                          fill="#4d8d95" rx="212.4" opacity="1"
                                                                          data-original="#1e2e33" class=""></rect>
                                                                    <g fill="#fff">
                                                                        <circle cx="256" cy="196.23" r="51.43"
                                                                                fill="#ffffff" opacity="1"
                                                                                data-original="#ffffff"
                                                                                class=""></circle>
                                                                        <path
                                                                                d="M231 260.49h50a64.44 64.44 0 0 1 64.44 64.44v27.7a14.55 14.55 0 0 1-14.55 14.55H181.08a14.55 14.55 0 0 1-14.55-14.55v-27.7A64.44 64.44 0 0 1 231 260.49z"
                                                                                fill="#ffffff" opacity="1"
                                                                                data-original="#ffffff" class=""></path>
                                                                    </g>
                                                                </g></svg>

                                                        @else
                                                            <img data-src="{{ asset('/img/users/' . $user->image) }}"
                                                                 class="lazy avatar rounded-pill shadow-lg" alt="User"
                                                                 style="height: 80px;width: 80px;">
                                                        @endif
                                                    </div>
                                                    <div class="content">
                                                        <div class="title-review">
                                                            <div class="title">
                                                                <h5>{{ $user->first_name . ' ' . $user->last_name }}</h5>
                                                                <span class="date">{{ date_format($review->created_at, 'F d, Y') }}</span>
                                                            </div>
                                                            <ul class="rating user-rating">
                                                                @for ($i = 0; $i < $review->rating; $i++)
                                                                    <li><i class="fas fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                        <p>{{ $review->comment }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-7 col-sm-9">
                    <div class="course-details-sidebar white-bg">
                        <div class="course-sidebar-thumb">
                            <img data-src="{{ asset('/img/courses/thumbnails/' . $details->thumbnail_image) }}"
                                 class="lazy"
                                 alt="image">
                            <a class="video-popup"
                               href="{{$details->video_intro?? ''}}"><i
                                        class="fas fa-play"></i></a>
                        </div>

                        <div class="course-sidebar-price mb-4">
                            @if ($details->pricing_type === 'premium')
                                <h3 class="title text-center text-primary">
                  <span
                          class="text-danger">{{ $position == 'left' ? $symbol : '' }}{{ number_format($details->previous_price) }}{{ $position == 'right' ? $symbol : '' }}</span>

                                    {{ $position === 'left' ? $symbol : '' }}{{ number_format($details->current_price) }}{{ $position == 'right' ? $symbol : '' }} @if (!is_null($details->previous_price))
                                    @endif

                                </h3>
                            @else
                                <h3 class="title text-center">{{ __('Free') }}</h3>
                            @endif
                        </div>
                        <div class="course-sidebar-btns p-3">
                            @if (session()->has('error'))
                                <div class="alert alert-danger d-flex" role="alert">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                    <p class="mr-1">{{ session()->get('error') }} </p>
                                </div>
                            @endif

                            @if (session()->has('warning'))
                                <div class="alert alert-warning d-flex" role="alert">
                                    <i class="fas fa-exclamation text-warning"></i>
                                    <p class="mr-1">{{ session()->get('warning') }}</p>
                                </div>
                            @endif

                            @error('attachment')
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror

                            @auth("web")
                                @if($isPurchase)

                                    <a href="{{route("user.my_course.curriculum",["id"=>$details->id])}}"
                                       class="btn btn-primary btn-lg w-100 mb-4"><i
                                                class="fal fa-play-circle"></i>
                                        مشاهدة الان
                                    </a>

                                @else
                                    <form action="{{ route('course.enrol', ['id' => $details->id]) }}"
                                          method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <button class="btn btn-primary btn-lg w-100 mb-4"
                                                type="submit"><i
                                                    class="fal fa-user-graduate"></i> اشترك ألآن
                                        </button>
                                    </form>

                                @endauth

                            @endif

                            @guest("web")

                                <form action="{{ route('course.enrol', ['id' => $details->id]) }}"
                                      method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <button class="btn btn-primary btn-lg font-weight-bold w-100 mb-4" type="submit">
                                        <i class="fal fa-user-graduate"></i>
                                        اشترك ألآن

                                    </button>
                                </form>
                            @endguest


                            <h6 class="title">{{ __('This Course Includes') }}</h6>

                            @php $features = explode(PHP_EOL, $details->features); @endphp

                            <ul class="course-include-list">
                                @foreach ($features as $feature)
                                    <li><i class="fal fa-check"></i> {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="course-sidebar-share d-flex flex-row justify-content-around">
                            <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                                        class="fab fa-facebook-f"></i></a>
                            <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                                        class="fab fa-twitter"></i></a>
                            <a
                                    href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"><i
                                        class="fab fa-linkedin"></i></a>
                        </div>
                    </div>

                    @if (count($relatedCourses) > 0)
                        <div class="trending-course">
                            <h4 class="title pb-4"><i class="fal fa-book"></i>دورات ذات صلة</h4>
                            @foreach ($relatedCourses as $course)
                                <div class="mb-3">
                                    @include("frontend.partials.single-course")
                                </div>

                            @endforeach
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </section>
    <!--====== COURSE DETAILS PART END ======-->
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let paymentMethodsSelect = $("#paymentMethodSelect");
            paymentMethodsSelect.niceSelect();

            paymentMethodsSelect.on("change", function (event) {

                let selected = event.target.value;
                let code_input = $("#code_input");
                if (selected === "cash") {
                    code_input.show();
                } else {
                    code_input.hide();
                }
            })


        });

    </script>

    <script>
        "use strict";
        let courseId = {{ $details->id }};
    </script>

    <script type="text/javascript" src="{{ asset('/js/course-details.js') }}"></script>
@endsection
