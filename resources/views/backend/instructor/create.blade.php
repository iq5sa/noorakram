@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Instructor') }}</h4>
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
                <a href="#">{{ __('Instructors') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Instructor') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ __('Add Instructor') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                       href="{{ route('admin.instructors', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <form id="ajaxForm" class="create" action="{{ route('admin.store_instructor') }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    <p id="err_image" class="mt-2 mb-0 text-danger em"></p>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ __('Language') . '*' }}</label>
                                            <select name="language_id" class="form-control">
                                                <option selected disabled>
                                                    {{ __('Select a Language') }}
                                                </option>

                                                @foreach ($languages as $language)
                                                    <option value="{{ $language->id }}">
                                                        {{ $language->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ __('Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="name"
                                                   placeholder="Enter Instructor Name">
                                            <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ __('Occupation') . '*' }}</label>
                                            <input type="text" class="form-control" name="occupation"
                                                   placeholder="Enter Instructor Occupation">
                                            <p id="err_occupation" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group pb-0">
                                            <label>{{ __('Description') . '*' }}</label>
                                            <textarea class="form-control summernote" name="description"
                                                      placeholder="Enter Instructor Description"
                                                      data-height="300"></textarea>
                                            <p id="err_description" class="mb-0 text-danger em"></p>
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
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
