<!DOCTYPE html>
<html>
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- title --}}
    <title>{{ __('Forget Password') . ' | ' . config('basics.website_title') }}</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">

    {{-- atlantis css --}}
    <link rel="stylesheet" href="{{ asset('/css/atlantis.css') }}">

    {{-- admin-login css also using for forget password --}}
    <link rel="stylesheet" href="{{ asset('/css/admin-login.css') }}">
</head>

<body>
{{-- forget password form start --}}
<div class="forget-page">
    @if (!empty($websiteInfo->logo))
        <div class="text-center mb-4">
            <img class="login-logo" src="{{ asset('/img/logo/logo.png') }}" alt="logo">
        </div>
    @endif

    <div class="form">
        <form class="forget-password-form" action="{{ route('admin.mail_for_forget_password') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter Your Email" value="{{ old('email') }}"/>
            @if ($errors->has('email'))
                <p class="text-danger text-left">{{ $errors->first('email') }}</p>
            @endif

            <button type="submit">{{ __('proceed') }}</button>
        </form>

        <a class="back-to-login" href="{{ route('admin.login') }}">
            &lt;&lt; {{ __('Back') }}
        </a>
    </div>
</div>
{{-- forget password form end --}}


{{-- jQuery --}}
<script src="{{ asset('/js/jquery-3.4.1.min.js') }}"></script>

{{-- popper js --}}
<script src="{{ asset('/js/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

{{-- bootstrap notify --}}
<script src="{{ asset('/js/bootstrap-notify.min.js') }}"></script>

{{-- fonts and icons script --}}
<script src="{{ asset('/js/webfont.min.js') }}"></script>

<script>
    "use strict";
    WebFont.load({
        google: {"families": ["Lato:300,400,700,900"]},
        custom: {
            "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
            urls: ['{{ asset("css/fonts.min.css") }}']
        },
        active: function () {
            sessionStorage.fonts = true;
        }
    });
</script>

@if (session()->has('success'))
    <script>
        "use strict";
        var content = {};

        content.message = '{{ session('success') }}';
        content.title = 'Success';
        content.icon = 'fa fa-bell';

        $.notify(content, {
            type: 'success',
            placement: {
                from: 'top',
                align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000
        });
    </script>
@endif

@if (session()->has('warning'))
    <script>
        "use strict";
        var content = {};

        content.message = '{{ session('warning') }}';
        content.title = 'Warning!';
        content.icon = 'fa fa-bell';

        $.notify(content, {
            type: 'warning',
            placement: {
                from: 'top',
                align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000
        });
    </script>
@endif
</body>
</html>
