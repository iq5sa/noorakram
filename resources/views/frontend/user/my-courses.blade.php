@php use Carbon\Carbon; @endphp
@extends('frontend.layout')

@section('pageHeading')
    {{ __('My Courses') }}
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => __('My Courses')])

    <!-- Start User Enrolled Course Section -->
    <section class="user-dashboard">
        <div class="container" id="scrollTarget">
            <div class="row">
                @includeIf('frontend.user.side-navbar')

                <div class="col-lg-9" id="#content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="account-info">
                                <div class="title">
                                    <h4>{{ __('All Courses') }}</h4>
                                </div>

                                <div class="main-info">
                                    <div class="main-table">
                                        @if (count($enrolments) == 0)
                                            <h5 class="text-center mt-3">{{ __('No Course Found') . '!' }}</h5>
                                        @else
                                            <div class="table-responsive">
                                                <table id="user-dataTable"
                                                       class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4">
                                                    <thead>
                                                    <tr>
                                                        <th>الدورة</th>
                                                        <th>مدة الدورة</th>
                                                        <th>الكلفة</th>
                                                        <th class="text-center">مشاهدة</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($enrolments as $enrolment)
                                                        <tr>
                                                            <td>
                                                                <a target="_blank" class="text-primary"
                                                                   href="{{ route('user.my_course.curriculum', ['id' => $enrolment->course_id, 'lesson_id' => $enrolment->lesson_id]) }}">
                                                                    <img alt="Course photo" class="rounded"
                                                                         src="{{asset('/img/courses/thumbnails/' .$enrolment->course->thumbnail_image)}}"
                                                                         style="height: 100px"/>
                                                                </a>
                                                            </td>

                                                            @php
                                                                $period = $enrolment->course->duration;
                                                                $array = explode(':', $period);
                                                                $hour = $array[0];
                                                                $courseDuration = Carbon::parse($period);
                                                            @endphp

                                                            <td class="pl-3">{{ $hour == '00' ? '00' : $courseDuration->format('h') }}
                                                                س {{ $courseDuration->format('i') }}د
                                                            </td>
                                                            <td>
                                                                @if (!is_null($enrolment->course_price))
                                                                    {{ $enrolment->currency_symbol_position == 'left' ? $enrolment->currency_symbol : '' }}{{ $enrolment->course_price }}{{ $enrolment->currency_symbol_position == 'right' ? $enrolment->currency_symbol : '' }}
                                                                @else
                                                                    <span
                                                                            class="{{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-1' }}">{{ __('Free') }}</span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                <a
                                                                        href="{{ route('user.my_course.curriculum', ['id' => $enrolment->course_id, 'lesson_id' => $enrolment->lesson_id]) }}"
                                                                        class="btn btn-success">
                                                                    <i class="fa fa-play-circle"></i>
                                                                    واصل التعلم
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End User Enrolled Course Section -->
@endsection
