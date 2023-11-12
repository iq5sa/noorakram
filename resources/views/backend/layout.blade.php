<!DOCTYPE html>
<html lang="en">
<head>
    {{-- required meta tags --}}
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>{{ __('Admin') . ' | ' . 'Dashboard' }}</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- include styles --}}
    @includeIf('backend.partials.styles')

    {{-- additional style --}}
    @yield('style')
</head>

<body data-background-color="light">
{{-- loader start --}}
<div class="request-loader">
    <img src="{{ asset('img/loader.gif') }}" alt="loader">
</div>
{{-- loader end --}}

<div class="wrapper">
    {{-- top navbar area start --}}
    @includeIf('backend.partials.top-navbar')
    {{-- top navbar area end --}}

    {{-- side navbar area start --}}
    @includeIf('backend.partials.side-navbar')
    {{-- side navbar area end --}}

    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                @yield('content')
            </div>
        </div>

        {{-- footer area start --}}
        @includeIf('backend.partials.footer')
        {{-- footer area end --}}
    </div>
</div>

{{-- include scripts --}}
@includeIf('backend.partials.scripts')

{{-- additional script --}}
@yield('script')
</body>
</html>
