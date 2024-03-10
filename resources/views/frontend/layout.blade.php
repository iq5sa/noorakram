<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  {{-- required meta tags --}}
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>@yield('pageHeading') {{ '| ' . config('app.name') }}</title>

  <meta name="keywords" content="@yield('metaKeywords')">
  <meta name="description" content="@yield('metaDescription')">

  {{-- fav icon --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('img/favicon/site.webmanifest')}}">
  <link rel="mask-icon" href="{{asset('img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">


  {{-- include styles --}}
  @includeIf('frontend.partials.styles')

  {{-- additional style --}}
  @yield('style')


  <!-- Google tag (gtag.js) -->
  <script async defer
          src="https://www.googletagmanager.com/gtag/js?id=G-1CQR17VV08"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-1CQR17VV08');
  </script>
</head>

<body>
{{-- preloader start --}}
<div id="preloader">
  <div id="status" class="">
    <span class="loader"></span>
  </div>
</div>

{{-- preloader end --}}

{{-- header start --}}
@if (!request()->routeIs('user.my_course.curriculum'))
  <header class="header-area header-area-one">
    {{-- include header-nav --}}
    @includeIf('frontend.partials.header.header-nav')
  </header>

@endif
{{-- header end --}}

@yield('content')

{{-- back to top start --}}
<div class="back-to-top">
  <a href="#">
    <i class="fal fa-chevron-double-up"></i>
  </a>
</div>

{{-- announcement popup --}}
@includeIf('frontend.partials.popups')
@includeIf('frontend.partials.search-popup')

{{-- cookie alert --}}
@if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
  @includeIf('cookieConsent::index')
@endif

{{-- include footer --}}
@if (!request()->routeIs('user.my_course.curriculum'))
  @includeIf('frontend.partials.footer.footer')
@endif

{{-- include scripts --}}
@includeIf('frontend.partials.scripts')


{{-- additional script --}}

@yield('script')
</body>
</html>
