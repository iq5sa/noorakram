@php use Carbon\Carbon; @endphp
@extends('frontend.layout')

@section('pageHeading')
    {{ __('Curriculum') }}
@endsection

@section('content')

    <!--====== CURRICULUM PART START ======-->
    <section class="course-video-section">
        <div class="course-navigation">
            <div class="navigation-container d-flex align-items-center justify-content-between">
                <div class="course-nav-left d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.my_courses') }}" class="prev"> الرجوع إلى الدورات</a>
                    <a href="#" class="course-nav-btn"><i class="far fa-bars"></i></a>
                </div>
                <div class="course-nav-right">

                    @if ($certificateStatus == 1)
                        <a href="{{ route('user.my_course.get_certificate', ['id' => request()->route('id')]) }}"
                           class="certificate"><i class="far fa-diploma"></i>{{ __('Certificate') }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="course-videos-area">
            <div class="container-fluid p-0">
                <div class="course-wrapper-video d-flex">
                    <div class="course-videos-sidebar">
                        <div class="course-video-nav mt-15">
                            @foreach ($modules as $key => $module)
                                <div class="course-section">
                                    <h5 class="heading">{{ $module->title }}</h5>

                                    @php $lessons = $module->lessons; @endphp

                                    <ul class="list">
                                        @foreach ($lessons as $lesson)
                                            @php
                                                $lessonPeriod = $lesson->duration;
                                                $lessonDuration = Carbon::parse($lessonPeriod);
                                            @endphp

                                            <li><a
                                                        href="{{ route('user.my_course.curriculum', ['id' => request()->route('id'), 'lesson_id' => $lesson->id]) }}"
                                                        class="{{ request()->input('lesson_id') == $lesson->id ? 'active' : '' }} {{ $lesson->lesson_complete()->where('user_id', Auth::guard('web')->user()->id)->count() > 0 ? 'lesson-complete' : '' }}"
                                                        id="lesson-{{ $lesson->id }}"><span>{{ $lesson->title }} {{ '(' . $lessonDuration->format('i') . ':' }}{{ $lessonDuration->format('s') . ')' }}</span></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="course-videos-wrapper">
                        <div class="breadcrumbs">
                            <div class="mb-20 d-flex flex-row justify-content-center align-items-center  bg-primary px-2 py-2">
                                <div class="breadcrumb-item">
                                    <a href="#" class="text-white">{{ $courseTitle ?? '' }}</a>
                                </div>

                                <div class="breadcrumb-item">
                                    <a href="#"
                                       class="text-white">{{ !empty($lessonTitle)?  $lessonTitle: '' }}</a>
                                </div>
                            </div>

                        </div>
                        @if (!empty($lessonContents))
                            @foreach ($lessonContents as $lessonContent)
                                @php $contentType = $lessonContent->type; @endphp

                                @switch($contentType)
                                    @case('video')
                                        @php
                                            $videoCompleted = $lessonContent->lesson_content_complete()->count();
                                        @endphp

                                        @php
                                            //converting steam data to json

                                          $steamData = json_decode($lessonContent->steam_data)

                                        @endphp

                                        <div class="video-box">

                                            <div style="position: relative; padding-top: 66.66666666666666%;">
                                                <iframe
                                                        src="https://customer-aati25w9dsztmksq.cloudflarestream.com/{{$steamData->uid?? ''}}/iframe?autoplay=true&poster={{$steamData->thumbnail?? ''}}&primaryColor=%2384b9bf"
                                                        style="border: none; position: absolute; top: 0; height: 84%; width: 100%;"
                                                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                                        allowfullscreen="true"
                                                ></iframe>
                                            </div>
                                        </div>
                                        @break
                                    @case('file')
                                        <div class="download-box">
                                            <h4>{{ $lessonContent->file_original_name }}</h4>
                                            <form class="d-inline-block"
                                                  action="{{ route('user.my_course.curriculum.download_file', ['id' => $lessonContent->id]) }}"
                                                  method="POST">
                                                @csrf
                                                <button type="submit"><span><i
                                                                class="fal fa-download"></i></span>{{ __('Download') }}
                                                </button>
                                            </form>
                                        </div>
                                        @break
                                    @case('text')
                                        <div class="content-box">
                                            {!! replaceBaseUrl($lessonContent->text, 'summernote') !!}
                                        </div>
                                        @break
                                    @case('code')
                                        <div class="content-box text-left" dir="ltr">
                                            <pre class="mb-0"><code>{{$lessonContent->code}}</code></pre>
                                        </div>
                                        @break
                                    @case('quiz')
                                        <div class="quiz-content-box" id="quiz-content"
                                             data-content_id="{{ $lessonContent->id }}"
                                             data-completion_status="{{ $lessonContent->completion_status }}">
                                            <span class="span">{{ __('Quiz') }}</span>

                                            @foreach ($quizzes as $quiz)
                                                <div class="quiz-box" @if (!$loop->first) style="display: none;" @endif>
                                                    <span class="count">{{ $loop->iteration . '/' . count($quizzes) }}</span>
                                                    <h4>{{ $quiz->question }}</h4>
                                                    <input type="hidden" value="{{ $quiz->id }}" class="quiz-id">

                                                    <p class="mb-3 text-left" id="{{ 'quiz-status-' . $quiz->id }}"></p>

                                                    @php $answers = json_decode($quiz->answers); @endphp

                                                    <div class="quiz-option">
                                                        <ul>
                                                            @foreach ($answers as $answer)
                                                                <li class="quiz-answer {{ 'quiz-option-' . $quiz->id }}"
                                                                    data-ans="{{ $answer->option }}">{{ $answer->option }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div id="quiz-complete" class="dis-none">
                                                <div id="quiz-complete-icon">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </div>
                                                <p>{{ __('You scored') }} <span
                                                            id="correct-ans-count"></span>/{{ count($quizzes) }} (<span
                                                            id="result-percentage"></span>%)</p>
                                                <a
                                                        href="{{ url()->current() . '?lesson_id=' . request()->input('lesson_id') . '&quiz=retake' }}">{{ __('Retake Quiz') }}</a>
                                            </div>

                                            <button class="btn btn-sm btn-info dis-none"
                                                    id="check-btn">{{ __('Check') }}</button>
                                            <button class="btn btn-sm btn-primary dis-none"
                                                    id="next-btn">{{ __('Next') }}</button>
                                        </div>
                                        @break
                                    @default
                                        {{-- do nothing --}}
                                @endswitch
                            @endforeach
                        @endif
                    </div>

                    <a id="scroll-to-quiz" href="#quiz-content"></a>
                </div>
            </div>
        </div>
    </section>
    <!--====== CURRICULUM PART END ======-->
@endsection

@section('script')

    <script>
        "use strict";
        const checkAnsUrl = "{{ route('user.my_course.curriculum.check_ans') }}";
        const quizStatus = "{{ request()->input('quiz') }}";
        const numOfQuiz = {{ !empty($quizzes) ? count($quizzes) : 0 }};
        const courseId = {{ request()->route('id') }};
        const lessonId = {{ request()->input('lesson_id') }};
        const quizScoreUrl = "{{ route('user.my_course.curriculum.store_quiz_score') }}";
        const contentCompletionUrl = "{{ route('user.my_course.curriculum.content_completion') }}";
        const certificateStatus = {{ $certificateStatus }};
    </script>

    <script type="text/javascript" src="{{ asset('/js/lesson-content.js') }}"></script>
@endsection


