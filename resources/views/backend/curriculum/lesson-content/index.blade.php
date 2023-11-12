@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Contents') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{route('admin.dashboard')}}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Course Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                        href="{{route('admin.course_management.courses', ['language' => $defaultLang->code])}}">{{ __('Courses') }}</a>
            </li>
            @if (!empty($courseInfo))
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a
                            href="#">{{ strlen($courseInfo->title) > 35 ? mb_substr($courseInfo->title, 0, 35, 'UTF-8') . '...' : $courseInfo->title }}</a>
                </li>
            @endif
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                        href="{{route('admin.course_management.course.modules', ['id' => $courseInfo->course_id, 'language' => $defaultLang->code])}}">{{ __('Modules') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $module->title }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                        href="#">{{ strlen($lesson->title) > 20 ? mb_substr($lesson->title, 0, 20, 'UTF-8') . '...' : $lesson->title }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">
                                {{ __('Contents') . ' (' . $language->name . ' ' . __('Language') . ')' }}
                            </div>
                        </div>

                        <div class="col-lg-8 mt-2 mt-lg-0">
                            <a
                                    href="{{ route('admin.course_management.course.modules', ['id' => request()->input('course'), 'language' => $language->code]) }}"
                                    class="btn btn-info btn-sm float-lg-right float-left"><i
                                        class="fas fa-backward"></i> {{ __('Back') }}
                            </a>

                            <a
                                    href="{{ route('admin.course_management.lesson.create_quiz', ['id' => $lesson->id, 'course' => request()->input('course')]) }}"
                                    class="btn btn-primary btn-sm float-lg-right float-left mr-2"><i
                                        class="fas fa-plus"></i> {{ __('Add Quiz') }}</a>

                            <a href="#" data-toggle="modal" data-target="#addCodeModal"
                               class="btn btn-primary btn-sm float-lg-right float-left mr-2"><i
                                        class="fas fa-plus"></i> {{ __('Add Code') }}</a>

                            <a href="#" data-toggle="modal" data-target="#addTextModal"
                               class="btn btn-primary btn-sm float-lg-right float-left mr-2"><i
                                        class="fas fa-plus"></i> {{ __('Add Text') }}</a>

                            <a href="#" data-toggle="modal" data-target="#addFileModal"
                               class="btn btn-primary btn-sm float-lg-right float-left mr-2"><i
                                        class="fas fa-plus"></i> {{ __('Add File') }}</a>

                            <a href="#" data-toggle="modal" data-target="#addVideoModal"
                               class="btn btn-primary btn-sm float-lg-right float-left mr-2"><i
                                        class="fas fa-plus"></i> {{ __('Add Video') }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @if (count($contents) == 0)
                                <h3 class="text-center mb-0">{{ __('No Content Found') . '!' }}</h3>
                            @else
                                <div class="alert alert-warning text-center mb-0" role="alert">
                                    <strong class="text-dark">{{ __('Drag & drop to sort the contents of this lesson.') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($contents) > 0)
        <div id="sort-content">
            @foreach ($contents as $content)
                <div class="row">
                    <div class="col">
                        <div class="card ui-state-default" data-id="{{ $content->id }}">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        @if ($content->type == 'video')
                                            <div class="card-title">{{ $content->video_original_name }}</div>
                                        @elseif ($content->type == 'file')
                                            <div class="card-title">{{ $content->file_original_name }}</div>
                                        @elseif ($content->type == 'text')
                                            <div class="card-title">{{ __('Text') }}</div>
                                        @elseif ($content->type == 'code')
                                            <div class="card-title">{{ __('Code') }}</div>
                                        @elseif ($content->type == 'quiz')
                                            <div class="card-title">{{ __('Quiz') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-lg-4">
                                        @if ($content->type != 'quiz')
                                            <form class="deleteForm"
                                                  action="{{ route('admin.course_management.lesson.delete_content', ['id' => $content->id]) }}"
                                                  method="POST">

                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger float-right deleteBtn">
                          <span class="btn-label">
                            <i class="fas fa-trash"></i>
                          </span>
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endif

                                        @if ($content->type == 'file')
                                            <form action="{{ route('admin.course_management.lesson.download_file', ['id' => $content->id]) }}"
                                                  method="GET">
                                                <button type="submit" class="btn btn-sm btn-success float-right mr-2">
                          <span class="btn-label">
                            <i class="fas fa-download"></i>
                          </span>
                                                    {{ __('Download') }}
                                                </button>
                                            </form>
                                        @endif

                                        @if ($content->type == 'text' || $content->type == 'code')
                                            <a href="#"
                                               class="btn btn-sm btn-secondary float-right mr-2 editBtn text-light"
                                               data-toggle="modal"
                                               data-target="{{ $content->type == 'text' ? '#editTextModal' : '#editCodeModal' }}"
                                               @if ($content->type == 'text') data-id="{{ $content->id }}"
                                               @else data-content_id="{{ $content->id }}"
                                               @endif @if ($content->type == 'text') data-text="{{ $content->text }}"
                                               @else data-code="{{ $content->code }}" @endif>
                        <span class="btn-label">
                          <i class="fas fa-edit"></i>
                        </span>
                                                {{ __('Edit') }}
                                            </a>
                                        @endif

                                        @if ($content->type == 'quiz')
                                            <a
                                                    href="{{ route('admin.course_management.lesson.manage_quiz', ['id' => $lesson->id, 'course' => request()->input('course'), 'language' => $language->code]) }}"
                                                    class="btn btn-sm btn-info float-right text-light">
                        <span class="btn-label">
                          <i class="fas fa-cog"></i>
                        </span>
                                                {{ __('Manage') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($content->type == 'video' || $content->type == 'text' || $content->type == 'code')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            @if ($content->type == 'video')
                                                <video width="400" controls>
                                                    <source src="{{ asset('/video/' . $content->video_unique_name) }}"
                                                            type="video/mp4">
                                                    {{ __('Your browser does not support HTML video.') }}
                                                </video>
                                            @elseif ($content->type == 'text')
                                                <div class="{{ $language->direction == 1 ? 'rtl' : '' }}">
                                                    {!! replaceBaseUrl($content->text, 'summernote') !!}
                                                </div>
                                            @elseif ($content->type == 'code')

                                                @php
                                                    $code = $content->code;
                                                @endphp
                                                <pre class="mb-0"><code>{{ $code }}</code></pre>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- add video modal --}}
    @include('backend.curriculum.lesson-content.video')

    {{-- add file modal --}}
    @include('backend.curriculum.lesson-content.file')

    {{-- add text modal --}}
    @include('backend.curriculum.lesson-content.create-text')

    {{-- edit text modal --}}
    @include('backend.curriculum.lesson-content.edit-text')

    {{-- add code modal --}}
    @include('backend.curriculum.lesson-content.create-code')

    {{-- edit code modal --}}
    @include('backend.curriculum.lesson-content.edit-code')
@endsection

@section('script')
    <script>
        "use strict";
        const vidUpUrl = "{{ route('admin.course_management.lesson.upload_video') }}";
        const vidRmvUrl = "{{ route('admin.course_management.lesson.remove_video') }}";

        const fileUpUrl = "{{ route('admin.course_management.lesson.upload_file') }}";
        const fileRmvUrl = "{{ route('admin.course_management.lesson.remove_file') }}";

        const sortContentUrl = "{{ route('admin.course_management.lesson.sort_contents') }}";
    </script>

    <script type="text/javascript" src="{{ asset('/js/dropzone-video-upload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/dropzone-file-upload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin-partial.js') }}"></script>
@endsection
