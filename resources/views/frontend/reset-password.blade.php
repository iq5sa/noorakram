@extends('frontend.layout')

@section('pageHeading')
    {{ __('Reset Password') }}
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Reset Password')])

    <!--====== Reset Password Part Start ======-->
    <div class="user-area-section pt-50 pb-50">
        <div class="container">
            <div class="row bg-white shadow rounded">
                <div class="col-lg-6 align-self-center">
                    <form action="{{ route('user.reset_password_submit') }}" method="POST">
                        @csrf
                        <div class="h3 text-center text-secondary mb-4">إعادة تعيين كلمة المرور الجديدة</div>
                        <div class="input-box mb-4">
                            <label for="password" class="text-secondary label">{{ __('New Password') . ':' }}</label>
                            <div class="input-group ">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fal fa-key"></i>
                                    </div>
                                </div>

                                <input id="password" type="password" class="form-control" placeholder="******"
                                       name="new_password" required>

                            </div>
                            @error('new_password')
                            <p class="text-danger mb-3">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="input-box mb-4">
                            <label for="new_password_confirmation"
                                   class="label text-secondary">{{ __('Confirm New Password') . ':' }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fal fa-key"></i>
                                    </div>
                                </div>
                                <input id="new_password_confirmation" type="password" placeholder="******"
                                       class="form-control"
                                       name="new_password_confirmation" required>

                            </div>
                            @error('new_password_confirmation')
                            <p class="text-danger mb-3">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="form_group input-box">
                            <button type="submit" class="btn btn-primary rounded">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 p-0 d-lg-block d-none">
                    <img src="https://colorlib.com/etc/regform/colorlib-regform-15/images/signup-img.jpg"
                         class="img-fluid" alt=""/>
                </div>
            </div>
        </div>
    </div>
    <!--====== Reset Password Part End ======-->
@endsection
