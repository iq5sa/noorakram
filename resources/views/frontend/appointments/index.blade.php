@extends('frontend.layout')

@section('pageHeading', $pageHeading ?? 'حجز موعد')
@section('metaKeywords', $seoInfo->meta_keyword_contact ?? '')
@section('metaDescription', $seoInfo->meta_description_contact ?? '')


@section("style")
    <link rel="stylesheet" href="{{asset("css/flatpickr.min.css")}}">
    <style>
        .card-title {
            margin: 0;
            color: black;
        }

        .flatpickr-calendar {
            direction: rtl;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
        }

        .flatpickr-current-month input.cur-year {
            padding-right: 1.5ch;
        }

        .time-tag {
            display: inline-block;
            padding: 10px 15px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background-color: var(--primary);
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: textfield;
            margin: 0;
        }

        ul li {
            list-style: auto;
        }
    </style>
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'حجز موعد'])

    <section class="pt-25 pb-120">
        @if(count($availableDates) == 0)
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="alert alert-info text-center" role="alert">
                            <strong>لا توجد مواعيد للحجز حالياً حاول في وقت لحق.</strong>
                        </div>
                    </div>
                </div>
            </div>

        @else

            <form action="{{ route('appointment.book.store') }}" method="post">
                @csrf
                <div class="container">
                    <div class="mb-3 row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">موعد الجلسة</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="date-picker">اختر التاريخ:</label>
                                                        <input value="{{old('date')}}" name="date" id="date-picker"
                                                               required type="text"
                                                               class="date-picker form-control d-none">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-12 mt-lg-0 mt-3">
                                                    <div class="time-picker">

                                                        <label for="timeLabels">اختر الوقت:</label>

                                                        <div class="time-labels" id="timeLabels"></div>
                                                        @error('time')
                                                        <div class="invalid-feedback d-block mt-2">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="card-title">معلومات الحجز والتكلفة</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                    <div class="input-box">
                                                        <label for="duration-select">اختر مدة الجلسة:</label>
                                                        <div class="input-group">
                                                            <select class="custom-select rounded-right" required
                                                                    id="duration-select" name="session_duration">
                                                                <option value="30">30 دقيقة
                                                                </option>
                                                                <option value="60">60 دقيقة</option>
                                                                <option value="90">90 دقيقة</option>
                                                                <option value="120">120 دقيقة</option>
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text rounded-left"
                                                                      id="totalPriceInput">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @error('session_duration')
                                                        <div class="invalid-feedback d-block mt-2">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                        <small class="text-info">
                                                            <span>تكلفة الدقيقة الواحدة</span>
                                                            <span>{{ env('APPOINTMENT_MINUTES_PRICE',2500) }} دينار عراقي</span>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                    <div class="form-group">
                                                        <label for="sessionType">نوع الجلسة</label>
                                                        <select id="sessionType" name="session_type" required
                                                                class="custom-select">
                                                            <option name="جلسة للام">جلسة للام</option>
                                                            <option name="جلسة تربوية">جلسة تربوية</option>
                                                        </select>
                                                        @error('session_type')
                                                        <div class="invalid-feedback d-block mt-2">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="card-title">المعلومات الشخصية</div>
                                        <small class="text-info">المعلومات الشخصية لا تستخدم إلا للتواصل معكم
                                            فقط.</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                <div class="input-box">
                                                    <label for="subject">الموضوع</label>
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <i class="fal fa-quote-left fa-1x font-weight-light"></i>
                                                            </div>
                                                        </div>
                                                        <input value="{{old('subject')}}" autocomplete="true"
                                                               id="subject" name="subject"
                                                               type="text"
                                                               required
                                                               placeholder="الموضوع" class="form-control">

                                                        @error('subject')
                                                        <div class="invalid-feedback d-block mt-2">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            @guest
                                                <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                    <div class="input-box">
                                                        <label for="name">الاسم الكامل</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fal fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input value="{{old('name')}}" autocomplete="true"
                                                                   name="name" id="name" type="text"
                                                                   required
                                                                   placeholder="الاسم الكامل"
                                                                   class="form-control">

                                                            @error('name')
                                                            <div class="invalid-feedback d-block mt-2">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                </div>
                                            @endguest
                                        </div>
                                        <div class="row">
                                            @guest
                                                <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                    <div class="input-box">
                                                        <label for="phone_number">رقم الهاتف</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fal fa-phone fa-1x font-weight-light"></i>
                                                                </div>
                                                            </div>

                                                            <input value="{{old('phone_number')}}" autocomplete="true"
                                                                   name="phone_number"
                                                                   id="phone_number" type="number"
                                                                   required
                                                                   placeholder=" مثال: 077xxxxxxx" class="form-control">

                                                            @error('phone_number')
                                                            <div class="invalid-feedback d-inline mt-2">
                                                                {{ $message }}
                                                            </div>

                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                                    <div class="input-box">
                                                        <label for="email">البريد الإلكتروني</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fal fa-envelope"></i>
                                                                </div>
                                                            </div>
                                                            <input value="{{old('email')}}" autocomplete="true"
                                                                   name="email" id="email"
                                                                   type="email" required
                                                                   placeholder="البريد الإلكتروني"
                                                                   class="form-control">
                                                            @error('email')
                                                            <div class="invalid-feedback d-block mt-2">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endguest
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <button class="btn btn-lg btn-primary" type="submit">حجز الموعد</button>
                        </div>
                    </div>

                </div>
            </form>

        @endif

        @guest
            @include('frontend.partials.login-hint-popup')
        @endguest
    </section>
@endsection

@section("script")

    <script src="{{asset("js/flatpickr")}}"></script>
    <script src="{{asset('js/flatpickr-ar.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>

        $(document).ready(function () {
            let timeTags = $("#time-tags");
            let enableDates = @json($availableDates);
            $(".date-picker").flatpickr({
                enable: enableDates,
                dateFormat: "Y-m-d",
                defaultDate: [enableDates[0]],
                inline: true,
                enableTime: false,
                disableMobile: true,
                position: 'start',
                locale: 'ar',
                onChange: function (selectedDates, dateStr, instance) {


                },
            });

            updateTimeOptions();

            $(document).on('click', '.time-tag', function () {
                $('.time-tag').removeClass('active');
                $(this).addClass('active');
                $('.time-tag input').prop('checked', false);
                $(this).find('input').prop('checked', true);
            });
        });

        let durationSelect = $("#duration-select");
        let totalPriceInput = $("#totalPriceInput");
        let staticPrice = {{env('APPOINTMENT_MINUTES_PRICE',2500)}}
            let
        duration = durationSelect.val();
        let totalPrice = duration * staticPrice;
        let currency = "IQD";
        updateAppointmentPrice();
        durationSelect.on("change", updateAppointmentPrice);

        function updateAppointmentPrice() {
            duration = parseInt(durationSelect.val());
            let calculatePrice = duration * staticPrice;
            totalPrice = calculatePrice
            totalPriceInput.text(totalPrice.toLocaleString() + ' ' + currency)
            return calculatePrice;
        }

        function updateTimeOptions() {
            const availableTimes = @json($availableTimes);
            const timeSelect = $('#timeLabels');
            timeSelect.empty();

            let buttonGroup = $('<div class="btn-group-toggle" data-toggle="buttons"></div>');
            availableTimes.forEach(time => {
                let timeTag = `<label class="btn btn-outline-primary time-tag rounded-pill">${time}<input type="radio" name="time" autocomplete="off" value="${time}"></label>`;
                buttonGroup.append(timeTag);
            });

            timeSelect.append(buttonGroup);
        }

    </script>
@endsection