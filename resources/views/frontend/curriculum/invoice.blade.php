<!DOCTYPE html>
<html>
<head lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>
    {{-- required meta tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- title --}}
    <title>{{ 'Invoice | ' . config('app.name') }}</title>

    {{-- fav icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- styles --}}
    <link rel="stylesheet" href="{{ asset('/asset/css/bootstrap.min.css') }}">
</head>

<body>
<div class="my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo text-center" style="margin-bottom: {{$mb}};">
                    <img src="{{ asset('/img/' . $websiteInfo->logo) }}" alt="Company Logo">
                </div>

                <div class="bg-primary">
                    <h2 class="text-center text-light pt-2">
                        {{ __('ENROLMENT INVOICE') }}
                    </h2>
                </div>

                @php
                    $position = $enrolmentInfo->currency_text_position;
                    $currency = $enrolmentInfo->currency_text;
                @endphp

                <div class="row">
                    {{-- enrolment details start --}}
                    <div style="width: {{$width}}; margin-left: {{$ml}};">
                        <div class="mt-4 mb-1">
                            <h4><strong>{{ __('Enrolment Details') }}</strong></h4>
                        </div>

                        <p>
                            <strong>{{ __('Order ID') . ': ' }}</strong>{{ '#' . $enrolmentInfo->order_id }}
                        </p>

                        <p>
                            <strong>{{ __('Enrolment Date') . ': ' }}</strong>{{ date_format($enrolmentInfo->created_at, 'M d, Y') }}
                        </p>

                        <p>
                            <strong>{{ __('Course') . ': ' }}</strong>{{ $courseInfo->title }}
                        </p>

                        <p>
                            <strong>{{ __('Course Price') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($enrolmentInfo->course_price) ? '0.00' : $enrolmentInfo->course_price }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        </p>

                        <p>
                            <strong>{{ __('Discount') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($enrolmentInfo->discount) ? '0.00' : $enrolmentInfo->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        </p>

                        <p>
                            <strong>{{ __('Grand Total') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($enrolmentInfo->grand_total) ? '0.00' : $enrolmentInfo->grand_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        </p>

                        <p>
                            <strong>{{ __('Payment Method') . ': ' }}</strong>{{ is_null($enrolmentInfo->payment_method) ? '-' : $enrolmentInfo->payment_method }}
                        </p>

                        <p>
                            <strong>{{ __('Payment Status') . ': ' }}</strong>@if ($enrolmentInfo->payment_status == 'completed')
                                {{ __('Completed') }}
                            @elseif ($enrolmentInfo->payment_status == 'pending')
                                {{ __('Pending') }}
                            @elseif ($enrolmentInfo->payment_status == 'rejected')
                                {{ __('Rejected') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    {{-- enrolment details start --}}

                    {{-- billing details start --}}
                    <div style="width: {{$width}}; float: {{$float}};">
                        <div class="mt-4 mb-1">
                            <h4><strong>{{ __('Billing Details') }}</strong></h4>
                        </div>

                        <p>
                            <strong>{{ __('Name') . ': ' }}</strong>{{ $enrolmentInfo->billing_first_name . ' ' . $enrolmentInfo->billing_last_name }}
                        </p>

                        <p>
                            <strong>{{ __('Email') . ': ' }}</strong>{{ $enrolmentInfo->billing_email }}
                        </p>

                        <p>
                            <strong>{{ __('Contact Number') . ': ' }}</strong>{{ $enrolmentInfo->billing_contact_number }}
                        </p>

                        <p>
                            <strong>{{ __('Address') . ': ' }}</strong>{{ $enrolmentInfo->billing_address }}
                        </p>

                        <p>
                            <strong>{{ __('City') . ': ' }}</strong>{{ $enrolmentInfo->billing_city }}
                        </p>

                        <p>
                            <strong>{{ __('State') . ': ' }}</strong>{{ is_null($enrolmentInfo->billing_state) ? '-' : $enrolmentInfo->billing_state }}
                        </p>

                        <p>
                            <strong>{{ __('Country') . ': ' }}</strong>{{ $enrolmentInfo->billing_country }}
                        </p>
                    </div>
                    {{-- billing details end --}}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
