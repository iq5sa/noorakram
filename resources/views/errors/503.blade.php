<!DOCTYPE html>
<html lang="en">
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- title --}}
    <title>{{ 'Maintenance Mode | ' . config('app.name') }}</title>

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          rel="stylesheet">

    {{-- 503 css --}}
    <link rel="stylesheet" href="{{ asset('/css/503.css') }}">
</head>

<body>
<div class="container">
    <div class="content">
        <div class="row mt-4">
            <div class="col-lg-4 offset-lg-4">
                <div class="maintanance-img-wrapper">
                    <img src="{{ asset('/img/' . $info->maintenance_img) }}" alt="image">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-8 offset-lg-2">
                <h3 class="maintanance-txt"
                    style="font-family: Tajawal, sans-serif">{!! nl2br($info->maintenance_msg) !!}</h3>
            </div>
        </div>
    </div>
</div>
</body>
</html>
