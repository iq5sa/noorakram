{{-- fontawesome css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/fontawesome.min.css') }}">

{{-- flaticon css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/flaticon.css') }}">

{{-- magnific-popup css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/magnific-popup.css') }}">

{{-- owl-carousel css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/owl-carousel.min.css') }}">

{{-- nice-select css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/nice-select.css') }}">

{{-- slick css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/slick.css') }}">


{{-- datatables css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/datatables-1.10.23.min.css') }}">

{{-- datatables bootstrap css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/datatables.bootstrap4.min.css') }}">

{{-- monokai css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/monokai-sublime.css') }}">

{{-- jQuery-ui css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/jquery-ui.min.css') }}">

@if (request()->routeIs('user.my_course.curriculum'))
    {{-- video css --}}
    <link rel="stylesheet" href="{{ mix('/dist/css/video.min.css') }}">
@endif

{{-- main css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/default.min.css') }}">

{{-- main css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/main.css') }}">


{{-- responsive css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/responsive.css') }}">

{{-- mega-menu css --}}
<link rel="stylesheet" href="{{ mix('/dist/css/mega-menu.css') }}">

@if ($currentLanguageInfo->direction == 1)
    {{-- right-to-left css --}}
    <link rel="stylesheet" href="{{ mix('/dist/css/rtl.css') }}">

    {{-- right-to-left-responsive css --}}
    <link rel="stylesheet" href="{{ mix('/dist/css/rtl-responsive.css') }}">
@endif
