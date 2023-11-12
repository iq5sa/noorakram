@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Information') }}</h4>
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
                <a href="#">{{ __('Information') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_info') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update Information') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ __('Website Title') . '*' }}</label>
                                    <input type="text" class="form-control" name="website_title"
                                           value="{{ $data->website_title != null ? $data->website_title : '' }}"
                                           placeholder="Enter Website Title">
                                    @if ($errors->has('website_title'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('website_title') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Email Address') . '*' }}</label>
                                    <input type="email" class="form-control ltr" name="email_address"
                                           value="{{ $data->email_address != null ? $data->email_address : '' }}"
                                           placeholder="Enter Email Address">
                                    @if ($errors->has('email_address'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('email_address') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Contact Number') . '*' }}</label>
                                    <input type="text" class="form-control" name="contact_number"
                                           value="{{ $data->contact_number != null ? $data->contact_number : '' }}"
                                           placeholder="Enter Contact Number">
                                    @if ($errors->has('contact_number'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('contact_number') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Address') . '*' }}</label>
                                    <input type="text" class="form-control" name="address"
                                           value="{{ $data->address != null ? $data->address : '' }}"
                                           placeholder="Enter Address">
                                    @if ($errors->has('address'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('address') }}</p>
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