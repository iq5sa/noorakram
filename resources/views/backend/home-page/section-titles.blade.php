@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Section Titles') }}</h4>
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
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Section Titles') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.home_page.update_section_title', ['language' => request()->input('language')]) }}"
                      method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update Section Titles') }}</div>
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
                                    <label>{{ __('Featured Courses Section Title') }}</label>
                                    <input class="form-control" name="featured_courses_section_title"
                                           value="{{ empty($data->featured_courses_section_title) ? '' : $data->featured_courses_section_title }}"
                                           placeholder="Enter Featured Courses Section Title">
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Features Section Title') }}</label>
                                    <input class="form-control" name="features_section_title"
                                           value="{{ empty($data->features_section_title) ? '' : $data->features_section_title }}"
                                           placeholder="Enter Features Section Title">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Blog Section Title') }}</label>
                                    <input class="form-control" name="blog_section_title"
                                           value="{{ empty($data->blog_section_title) ? '' : $data->blog_section_title }}"
                                           placeholder="Enter Blog Section Title">
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
