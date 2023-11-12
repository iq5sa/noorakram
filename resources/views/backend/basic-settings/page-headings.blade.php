@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Page Headings') }}</h4>
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
                <a href="#">{{ __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Page Headings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_page_headings', ['language' => request()->input('language')]) }}"
                      method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update Page Headings') }}</div>
                            </div>

                            <div class="col-lg-2">
                                @includeIf('backend.partials.languages')
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ __('Blog Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="blog_page_title"
                                           value="{{ $data != null ? $data->blog_page_title : '' }}">
                                    @if ($errors->has('blog_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('blog_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Blog Details Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="blog_details_page_title"
                                           value="{{ $data != null ? $data->blog_details_page_title : '' }}">
                                    @if ($errors->has('blog_details_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('blog_details_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Contact Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="contact_page_title"
                                           value="{{ $data != null ? $data->contact_page_title : '' }}">
                                    @if ($errors->has('contact_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('contact_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Courses Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="courses_page_title"
                                           value="{{ $data != null ? $data->courses_page_title : '' }}">
                                    @if ($errors->has('courses_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('courses_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Course Details Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="course_details_page_title"
                                           value="{{ $data != null ? $data->course_details_page_title : '' }}">
                                    @if ($errors->has('course_details_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('course_details_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('FAQ Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="faq_page_title"
                                           value="{{ $data != null ? $data->faq_page_title : '' }}">
                                    @if ($errors->has('faq_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('faq_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Forget Password Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="forget_password_page_title"
                                           value="{{ $data != null ? $data->forget_password_page_title : '' }}">
                                    @if ($errors->has('forget_password_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('forget_password_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Instructors Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="instructors_page_title"
                                           value="{{ $data != null ? $data->instructors_page_title : '' }}">
                                    @if ($errors->has('instructors_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('instructors_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Login Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="login_page_title"
                                           value="{{ $data != null ? $data->login_page_title : '' }}">
                                    @if ($errors->has('login_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('login_page_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Signup Page Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="signup_page_title"
                                           value="{{ $data != null ? $data->signup_page_title : '' }}">
                                    @if ($errors->has('signup_page_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('signup_page_title') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
