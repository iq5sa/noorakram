@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Hero Section') }}</h4>
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
                <a href="#">{{ __('Hero Section') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update Hero Section') }}</div>
                        </div>

                        <div class="col-lg-2">
                            @includeIf('backend.partials.languages')
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="heroForm"
                                  action="{{ route('admin.home_page.update_hero_section', ['language' => request()->input('language')]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Background Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if (!empty($data->background_image))
                                            <img src="{{ asset('/img/hero-section/' . $data->background_image) }}"
                                                 alt="background image" class="uploaded-background-img">
                                        @else
                                            <img src="{{ asset('/img/noimage.jpg') }}" alt="..."
                                                 class="uploaded-background-img">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="background-img-input" name="background_image">
                                        </div>
                                    </div>
                                    @error('background_image')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('First Title') }}</label>
                                            <input type="text" class="form-control" name="first_title"
                                                   value="{{ empty($data->first_title) ? '' : $data->first_title }}"
                                                   placeholder="Enter First Title">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Second Title') }}</label>
                                            <input type="text" class="form-control" name="second_title"
                                                   value="{{ empty($data->second_title) ? '' : $data->second_title }}"
                                                   placeholder="Enter Second Title">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('First Button') }}</label>
                                            <input type="text" class="form-control" name="first_button"
                                                   value="{{ empty($data->first_button) ? '' : $data->first_button }}"
                                                   placeholder="Enter First Button Name">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('First Button URL') }}</label>
                                            <input type="url" class="form-control ltr" name="first_button_url"
                                                   value="{{ empty($data->first_button_url) ? '' : $data->first_button_url }}"
                                                   placeholder="Enter First Button URL">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Second Button') }}</label>
                                            <input type="text" class="form-control" name="second_button"
                                                   value="{{ empty($data->second_button) ? '' : $data->second_button }}"
                                                   placeholder="Enter Second Button Name">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Second Button URL') }}</label>
                                            <input type="url" class="form-control ltr" name="second_button_url"
                                                   value="{{ empty($data->second_button_url) ? '' : $data->second_button_url }}"
                                                   placeholder="Enter Second Button URL">
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="heroForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
