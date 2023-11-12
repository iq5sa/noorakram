<!DOCTYPE html>
<html lang="en">
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- title --}}
    <title>Certificate {{ '| ' . config('app.name') }}</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- fontawesome css --}}
    <link rel="stylesheet" href="{{ asset('/asset/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/asset/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/asset/css/certificate.css') }}">

</head>

<body>
<div class="container" id="certificate-container">
    <div class="certificate-main" id="course-certificate">
        <div class="certificate-wrapper text-center" style="background-image: url({{ asset('/img/banner.jpg') }});">
            <div class="certificate-top-content text-center">
                <img src="{{ asset('/img/design-01.png') }}" class="img-1" alt="design">
                <h1>{{ $certificateTitle }}</h1>
                <img src="{{ asset('/img/design-02.png') }}" class="img-2" alt="design">
            </div>

            <div class="main-content">
                <p>{!! nl2br($certificateText) !!}</p>
            </div>

            <div class="user-box">
                <h4>{{ $instructorInfo->name }}</h4>
                <h5>{{ $instructorInfo->name . ', ' . $instructorInfo->occupation }}</h5>
            </div>

            <div class="bottom-shape">
                <img src="{{ asset('/img/design-02.png') }}" alt="design">
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button class="btn btn-primary" id="print-btn"><i class="far fa-print"></i> {{ __('Print') }}</button>
</div>

<script type="text/javascript" src="{{ asset('/js/jquery-1.12.4.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/js/printThis.min.js') }}"></script>

<script>
    $(document).ready(function () {
        'use strict';

        $('#print-btn').on('click', function () {
            $('#course-certificate').printThis({
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('/asset/css/certificate.css') }}"
            });
        })
    });
</script>
</body>
</html>
