@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Course') }}</h4>
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
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Course') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Course') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                       href="{{ route('admin.course_management.courses', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1 dis-none" id="courseErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <form id="courseForm"
                                  action="{{ route('admin.course_management.update_course', ['id' => $course->id]) }}"
                                  method="POST" enctype="multipart/form-data">

                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('/img/courses/thumbnails/' . $course->thumbnail_image) }}"
                                             alt="thumbnail image" class="uploaded-thumb-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="thumb-img-input" name="thumbnail_image">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-1">
                                    <label for="">{{ __('Introduction Video') . '*' }}</label>
                                    <input type="file" accept="video/mp4" class="form-control" name="video_intro"
                                           placeholder="Enter Video Link">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="">{{ __('Cover Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('/img/courses/covers/' . $course->cover_image) }}"
                                             alt="cover image"
                                             class="uploaded-cover-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="cover-img-input" name="cover_image">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-1">
                                    <label for="">{{ __('Pricing Type') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="pricing_type" value="free"
                                                   class="selectgroup-input" {{ $course->pricing_type == 'free' ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Free') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="pricing_type" value="premium"
                                                   class="selectgroup-input" {{ $course->pricing_type == 'premium' ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Premium') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row {{ $course->pricing_type == 'free' ? 'd-none' : '' }}" id="price-input">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Current Price') . '*' }}</label>
                                            <input type="number" step="0.01" name="current_price"
                                                   placeholder="Enter Current Price"
                                                   class="form-control" value="{{ $course->current_price }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Previous Price') }}</label>
                                            <input type="number" step="0.01" name="previous_price"
                                                   placeholder="Enter Previous Price"
                                                   class="form-control" value="{{ $course->previous_price }}">
                                        </div>
                                    </div>
                                </div>

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        @php
                                            $courseData = $language->courseInformation()->where('course_id', $course->id)->first();
                                        @endphp

                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                            data-target="#collapse{{ $language->id }}"
                                                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                            aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                 class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                 aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Title') . '*' }}</label>
                                                                <input type="text" class="form-control"
                                                                       name="{{ $language->code }}_title"
                                                                       placeholder="Enter Title"
                                                                       value="{{ is_null($courseData) ? '' : $courseData->title }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php
                                                                    $categories = DB::table('course_categories')
                                                                      ->where('language_id', $language->id)
                                                                      ->where('status', 1)
                                                                      ->orderByDesc('id')
                                                                      ->get();
                                                                @endphp

                                                                <label for="">{{ __('Category') . '*' }}</label>
                                                                <select name="{{ $language->code }}_category_id"
                                                                        class="form-control">
                                                                    @if (is_null($categories))
                                                                        <option selected
                                                                                disabled>{{ __('Select Category') }}</option>
                                                                    @else
                                                                        <option disabled
                                                                                selected>{{ __('Select Category') }}</option>

                                                                        @foreach ($categories as $category)
                                                                            <option
                                                                                    value="{{ $category->id }}" {{ !empty($courseData) && $courseData->course_category_id == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php
                                                                    $instructors = DB::table('instructors')
                                                                      ->where('language_id', $language->id)
                                                                      ->orderByDesc('id')
                                                                      ->get();
                                                                @endphp

                                                                <label for="">{{ __('Instructor') . '*' }}</label>
                                                                <select name="{{ $language->code }}_instructor_id"
                                                                        class="form-control mb-2">
                                                                    @if (is_null($instructors))
                                                                        <option selected
                                                                                disabled>{{ __('Select Instructor') }}</option>
                                                                    @else
                                                                        <option disabled
                                                                                selected>{{ __('Select Instructor') }}</option>

                                                                        @foreach ($instructors as $instructor)
                                                                            <option
                                                                                    value="{{ $instructor->id }}" {{ !empty($courseData) && $courseData->instructor_id == $instructor->id ? 'selected' : '' }}>
                                                                                {{ $instructor->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>

                                                                <a href="{{ route('admin.instructors', ['language' => $defaultLang->code]) }}"
                                                                   target="_blank" id="instructor-link"
                                                                   class="text-warning">
                                                                    {{ __('Click this link to add a new instructor') . '.' }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Features') . '*' }}</label>
                                                                <textarea class="form-control"
                                                                          name="{{ $language->code }}_features"
                                                                          placeholder="Enter Course Features"
                                                                          rows="7">{{ is_null($courseData) ? '' : $courseData->features }}</textarea>
                                                                <p class="text-warning mt-1 mb-0">
                                                                    {{ __('To seperate the features, enter a new line after each feature.') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Description') . '*' }}</label>
                                                                <textarea class="form-control summernote"
                                                                          name="{{ $language->code }}_description"
                                                                          placeholder="Enter Course Description"
                                                                          data-height="300">{{ is_null($courseData) ? '' : replaceBaseUrl($courseData->description, 'summernote') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Keywords') }}</label>
                                                                <input class="form-control"
                                                                       name="{{ $language->code }}_meta_keywords"
                                                                       placeholder="Enter Meta Keywords"
                                                                       data-role="tagsinput"
                                                                       value="{{ is_null($courseData) ? '' : $courseData->meta_keywords }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Description') }}</label>
                                                                <textarea class="form-control"
                                                                          name="{{ $language->code }}_meta_description"
                                                                          rows="5"
                                                                          placeholder="Enter Meta Description">{{ is_null($courseData) ? '' : $courseData->meta_description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="courseForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('/js/admin-partial.js') }}"></script>
@endsection
