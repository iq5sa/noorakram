<!DOCTYPE html>
<html>
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- title --}}
    <title>{{ __('Admin Login') . ' | ' . $websiteInfo->website_title }}</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- login css --}}
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>

<body>
{{-- login form start --}}
<div class="login-page">
    <div class="text-center mb-4">
        <img class="login-logo" src="{{ asset('img/logo/logo.png')}}" alt="logo">
    </div>

    <div class="form">
        @if (session()->has('alert'))
            <div class="alert alert-danger fade show" role="alert">
                <strong>{{ session('alert') }}</strong>
            </div>
        @endif

        <form class="login-form" action="{{ route('admin.auth') }}" method="POST">
            @csrf
            <input type="text" name="username" placeholder="Enter Username"/>
            @if ($errors->has('username'))
                <p class="text-danger text-left">{{ $errors->first('username') }}</p>
            @endif

            <input type="password" name="password" placeholder="Enter Password"/>
            @if ($errors->has('password'))
                <p class="text-danger text-left">{{ $errors->first('password') }}</p>
            @endif

            <button type="submit">{{ __('login') }}</button>
        </form>

        <a class="forget-link" href="{{ route('admin.forget_password') }}">
            {{ __('Forget Password or Username?') }}
        </a>
    </div>
</div>
{{-- login form end --}}


{{-- jQuery --}}
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

{{-- popper js --}}
<script src="{{ asset('js/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>
