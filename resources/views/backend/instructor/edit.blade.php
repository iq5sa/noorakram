@extends('backend.layout')
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Instructor') }}</h4>
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
                <a href="#">{{ __('Edit Instructor') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ __('Edit Instructor') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                       href="{{ route('admin.instructors', ['language' => request()->input('language')]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <form id="ajaxEditForm"
                                  action="{{ route('admin.update_instructor', ['id' => $instructor->id]) }}"
                                  method="POST" enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('/img/instructors/' . $instructor->image) }}"
                                             alt="image"
                                             class="uploaded-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    <p id="editErr_image" class="mt-2 mb-0 text-danger em"></p>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ __('Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="name"
                                                   placeholder="Enter Instructor Name"
                                                   value="{{ $instructor->name }}">
                                            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ __('Occupation') . '*' }}</label>
                                            <input type="text" class="form-control" name="occupation"
                                                   placeholder="Enter Instructor Occupation"
                                                   value="{{ $instructor->occupation }}">
                                            <p id="editErr_occupation" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group pb-0">
                                            <label>{{ __('Description') . '*' }}</label>
                                            <textarea class="form-control summernote" name="description"
                                                      placeholder="Enter Instructor Description"
                                                      data-height="300">{{ $instructor->description }}</textarea>
                                            <p id="editErr_description" class="mb-0 text-danger em"></p>
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
                            <button type="submit" class="btn btn-success" id="updateBtn">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
