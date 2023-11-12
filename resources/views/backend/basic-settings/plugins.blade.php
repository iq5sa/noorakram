@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Plugins') }}</h4>
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
                <a href="#">{{ __('Plugins') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_disqus') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('Disqus') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Disqus Status*') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="1"
                                                   class="selectgroup-input" {{ $data->disqus_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="0"
                                                   class="selectgroup-input" {{ $data->disqus_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('disqus_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('disqus_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Disqus Short Name*') }}</label>
                                    <input type="text" class="form-control" name="disqus_short_name"
                                           value="{{ $data->disqus_short_name }}">

                                    @if ($errors->has('disqus_short_name'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('disqus_short_name') }}</p>
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

        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_recaptcha') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ __('Google Recaptcha') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Recaptcha Status*') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_recaptcha_status" value="1"
                                                   class="selectgroup-input" {{ $data->google_recaptcha_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="google_recaptcha_status" value="0"
                                                   class="selectgroup-input" {{ $data->google_recaptcha_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('google_recaptcha_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_recaptcha_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Recaptcha Site Key*') }}</label>
                                    <input type="text" class="form-control" name="google_recaptcha_site_key"
                                           value="{{ $data->google_recaptcha_site_key }}">

                                    @if ($errors->has('google_recaptcha_site_key'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_recaptcha_site_key') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Recaptcha Secret Key*') }}</label>
                                    <input type="text" class="form-control" name="google_recaptcha_secret_key"
                                           value="{{ $data->google_recaptcha_secret_key }}">

                                    @if ($errors->has('google_recaptcha_secret_key'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('google_recaptcha_secret_key') }}</p>
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
