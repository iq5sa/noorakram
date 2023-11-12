@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->forget_password_page_title ?? 'Forgot Password' }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_forget_password }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_forget_password }}
    @endif
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => $pageHeading->forget_password_page_title ?? 'Forgot Password'])

    <!--====== Forget Password Part Start ======-->
    <div class="user-area-section pt-50 pb-50">
        <div class="container">
            <div class="row justify-content-center bg-white shadow rounded">
                <div class="col-lg-6 align-self-center pt-lg-0 pt-5">
                    <div class="user-form">
                        <div class="h3 text-center text-secondary mb-4">نسيت كلمة المرور؟</div>
                        <p class="text-secondary pb-1">يرجى كتابة البريد الإلكتروني الخاص بك في الحقل أدناه </p>

                        <form action="{{ route('user.send_forget_password_mail') }}" method="POST">
                            @csrf
                            <div class="input-box mb-4">
                                <label for="email" class="label text-secondary">{{ __('Email Address') . ':' }}</label>

                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fal fa-envelope"></i>
                                        </div>
                                    </div>
                                    <input id="email" type="email" class="form-control" name="email"
                                           placeholder="name@gmail.com"
                                           value="{{ old('email') }}">


                                </div>

                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-box mb-4">
                                <div class="form_group input-box">
                                    <button type="submit" class="btn btn-primary">إرسال</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <img src="{{asset("img/Forgot password-bro.svg")}}" class="d-lg-block d-none img-fluid"
                         alt="Forgot password"/>
                </div>
            </div>
        </div>
    </div>
    <!--====== Forget Password Part End ======-->
@endsection
