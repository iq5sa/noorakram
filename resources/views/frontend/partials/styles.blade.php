{{-- main css --}}
<link rel="stylesheet" href="{{ asset('dist/css/main.css?' . config('app_version')) }}">


{{-- jQuery-ui css --}}
<link rel="stylesheet" href="{{ asset('dist/css/jquery-ui.min.css?v=' . config('app_version')) }}">

{{-- nice-select css --}}
<link rel="stylesheet" href="{{ asset('dist/css/nice-select.css?v=' . config('app_version')) }}">

{{-- slick css --}}
<link rel="stylesheet" href="{{ asset('dist/css/slick.css?v=' . config('app_version')) }}">


{{-- datatables css --}}
<link rel="stylesheet" href="{{ asset('dist/css/datatables-1.10.23.min.css?v=' . config('app_version')) }}">

{{-- datatables bootstrap css --}}
<link rel="stylesheet" href="{{ asset('dist/css/datatables.bootstrap4.min.css?v=' . config('app_version')) }}">

{{-- monokai css --}}
<link rel="stylesheet" href="{{ asset('dist/css/monokai-sublime.css?v=' . config('app_version')) }}">


@if (request()->routeIs('user.my_course.curriculum'))
  {{-- video css --}}
  <link rel="stylesheet" href="{{ asset('dist/css/video.min.css?v=' . config('app_version')) }}">
@endif

{{-- main css --}}
<link rel="stylesheet" href="{{ asset('dist/css/default.min.css?v=' . config('app_version')) }}">


{{-- flaticon css --}}
<link rel="stylesheet" href="{{ asset('dist/css/flaticon.css?v=' . config('app_version')) }}">
{{-- magnific-popup css --}}
<link rel="stylesheet" href="{{ asset('dist/css/magnific-popup.css?v=' . config('app_version')) }}">
{{-- owl-carousel css --}}
<link rel="stylesheet" href="{{ asset('dist/css/owl-carousel.min.css?v=' . config('app_version')) }}">


{{-- fontawesome css --}}
<link rel="stylesheet" href="{{ asset('dist/css/fontawesome.min.css?v=' . config('app_version')) }}">

{{-- responsive css --}}
<link rel="stylesheet" href="{{ asset('dist/css/responsive.css?v=' . config('app_version')) }}">

{{-- mega-menu css --}}
<link rel="stylesheet" href="{{ asset('dist/css/mega-menu.css?v=' . config('app_version')) }}">

{{-- right-to-left css --}}
<link rel="stylesheet" href="{{ asset('dist/css/rtl.css?v=' . config('app_version')) }}">

{{-- right-to-left-responsive css --}}
<link rel="stylesheet" href="{{ asset('dist/css/rtl-responsive.css?v=' . config('app_version')) }}">
<style>
  #label_new {
    position: absolute !important;
    top: 10px;
    left: -33px;
    padding: 5px 10px;
    background-color: #dc3545;
    color: #fff;
    font-size: 14px;
    border-radius: 20px;
    width: 121px;
    text-align: center;
    font-weight: bold;
    transform: rotate(321deg);
    z-index: 10;
  }
</style>
