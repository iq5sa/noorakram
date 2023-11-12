@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Video Section') }}</h4>
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
                <a href="#">{{ __('Video Section') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update Video Section') }}</div>
                        </div>

                        <div class="col-lg-2">
                            @includeIf('backend.partials.languages')
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="videoForm"
                                  action="{{ route('admin.home_page.update_video_section', ['language' => request()->input('language')]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if (!empty($data->image))
                                            <img src="{{ asset('/img/video-images/' . $data->image) }}" alt="image"
                                                 class="uploaded-img">
                                        @else
                                            <img src="{{ asset('/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    @error('image')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Title') }}</label>
                                            <input type="text" class="form-control" name="title"
                                                   value="{{ empty($data->title) ? '' : $data->title }}"
                                                   placeholder="Enter Video Section Title">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Link') . '*' }}</label>
                                            <input type="url" class="form-control ltr" name="link"
                                                   value="{{ empty($data->link) ? '' : $data->link }}"
                                                   placeholder="Enter Video Link">
                                            @error('link')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
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
                            <button type="submit" form="videoForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
