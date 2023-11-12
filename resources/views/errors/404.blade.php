<!DOCTYPE html>
<html>
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>404 Page Not found</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- include styles --}}
    @includeIf('frontend.partials.styles')

    {{-- additional style --}}
    @yield('style')
</head>

<body>

<!--====== 404 PART START ======-->
<section class="error-area d-flex align-items-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="error-content">
                <span>
                  {{ __('404! Page Not Found') }}
                </span>
                    <h2 class="title"></h2>
                    <ul>
                        <li><a href="{{ route('index') }}">{{ __('Get Back to Home') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="error-thumb">
        <img src="{{ asset('/img/error.png') }}" alt="error">
    </div>
</section>
<!--====== 404 PART ENDS ======-->
</body>

</html>
