@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_contact }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_contact }}
    @endif
@endsection
@section("style")
    <link rel="stylesheet" href="{{asset("css/flatpickr.min.css")}}">

    <style>
        .card-title {
            margin: 0;
            color: black;
        }


        .flatpickr-calendar {
            width: 100%;
            margin: auto;
            margin-top: .25rem !important;
        }
    </style>

@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'حجز موعد'])


    <section class="pt-25 pb-120">
        <form action="{{ route('appointment.payment') }}" method="post">
            @csrf
            <div class="container">
                <div class="mb-3 row">
                    <div class="col-lg-8">


                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="card-title">المعلومات الشخصية</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="input-box">
                                            <div class="input-group">
                                                <input id="subject" name="subject" type="text" required
                                                       placeholder="الموضوع"
                                                       class="form-control">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fal fa-quote-left fa-1x font-weight-light"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="input-box">
                                            <div class="input-group">
                                                <input name="name" type="text" required placeholder="الاسم الكامل"
                                                       class="form-control">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fal fa-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="input-box">
                                            <div class="input-group">
                                                <input name="phoneNumber" type="text" required placeholder="رقم الهاتف"
                                                       class="form-control">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fal fa-phone fa-1x font-weight-light"></i>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="input-box">
                                            <div class="input-group">
                                                <input name="email" type="email" required
                                                       placeholder="البريد الإلكتروني"
                                                       class="form-control">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fal fa-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">معلومات الحجز والتكلفة</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label for="duration-select">المدة:</label>
                                                <select class="" required id="duration-select">
                                                    <option value="30">30 دقيقة</option>
                                                    <option value="60">60 دقيقة</option>
                                                    <option value="90">90 دقيقة</option>
                                                    <option value="120">120 دقيقة</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="form-group">
                                            <label for="totalPriceInput">التكلفة:</label>
                                            <input name="totalPrice" type="text" readonly id="totalPriceInput"
                                                   class="form-control border rounded-pill"
                                                   placeholder="يجب تحديد عدد الدقائق">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 mb-xl-4 mb-3">
                                        <div class="form-group">
                                            <label for="sessionType">نوع الجلسة</label>
                                            <select id="sessionType" name="appointmentType" required
                                                    class="form-control rounded-pill">
                                                <option name="جلسة للام">جلسة للام</option>
                                                <option name="جلسة تربوية">جلسة تربوية</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">موعد الجلسة</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="input-group">
                                            <input id="date-picker" required type="text"
                                                   class="date-picker form-control border-primary rounded-pill"
                                                   placeholder="اختر تاريخ حجز الموعد ">
                                            <i class="fa fa-calendar-alt fa-lg font-weight-light"
                                               style="left: 17px"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">إتمام العملية</div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="paymentMethods">اختر بوابة الدفع</label>
                                    <select id="paymentMethods" required name="paymentMethod"
                                            class="form-control mb-4 rounded-pill">
                                        <option value="master">ماستر او فيزا / Mastercard or Visa</option>
                                        <option value="zainCash">زين كاش - Zain Cash</option>
                                        <option value="cash">نقد – Cash</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary rounded-pill" type="submit">حجز الموعد</button>
                                </div>

                            </div>
                        </div>

                    </div>


                </div>

            </div>
        </form>
    </section>

@endsection
@section("script")
    <script src="{{asset("js/flatpickr")}}"></script>
    <script src="{{asset('js/flatpickr-ar.js')}}"></script>

    <script>
        $(document).ready(function () {

            $(".date-picker").flatpickr({
                enable: ["2023-09-01", "2023-09-02", "2023-09-03", "2023-09-04", "2023-09-05", "2023-09-06"],
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                inline: true,
                disableMobile: "true",
                position: 'center',
                locale: 'ar',
                minuteIncrement: 30,
                onChange: function (selectedDates, dateStr, instance) {
                    console.log([selectedDates, dateStr, instance])
                },

            });

        });


    </script>


    <script>
        let durationSelect = $("#duration-select");
        let totalPriceInput = $("#totalPriceInput");
        let staticPrice = 2500;
        let duration = durationSelect.val();
        let totalPrice = duration * staticPrice;
        let currency = "IQD";
        updateAppointmentPrice();
        durationSelect.on("change", updateAppointmentPrice);

        function updateAppointmentPrice() {
            duration = parseInt(durationSelect.val());
            let calculatePrice = duration * staticPrice;
            totalPrice = calculatePrice // Collection total price for each minute

            totalPriceInput.val(totalPrice.toLocaleString() + ' ' + currency)

            return calculatePrice;
        }


    </script>
@endsection
