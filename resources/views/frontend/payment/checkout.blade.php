@extends('frontend.layout')
@section("pageHeading")
    {{$pageHeading}}
@endsection


@section('content')

    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => "إتمام عملية الشراء"])

    <!--====== COURSES PART START ======-->
    <section class="pt-50 pb-120">
        <div class="container">
            <div class="text-center pb-4">
                <h2 class="title mb-2">معلومات الدفع والشراء</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-lg-5 col-md-12">
                            <div class="card bg-warning rounded-lg shadow border-0 h-100">
                                <div class="card-body">
                                    <h5 class="card-title">معلومات الطلب</h5>
                                    <p class="card-text">
                                        <span>ستقوم ب-عملية شراء بقيمة</span>
                                        <span class="font-weight-bold text-danger"> {{number_format($order["price"])}} د.ع</span>
                                        يرجى الانتباه سيتم استقطاع المبلغ من وسيلة الدفع المختارة
                                    </p>
                                    <p class="card-text mb-3">
                                        <small class="text-dark">
                                            عند إتمام عملية الشراء سوف يتم تحويلك مباشرةً إلى مشاهدة الدورة
                                        </small>
                                    </p>

                                    <ul class="list-group mb-3">
                                        <li
                                                class="list-group-item border-0 bg-transparent px-1 d-flex justify-content-between align-items-center">
                                            <div class="ml-2">
                                                <p class="font-weight-bold">نوع الطلب</p>
                                                <small>{{$order["name"]}}</small>
                                            </div>
                                            @if($orderType =='course')
                                                <i class="fa fa-play-circle fa-lg"></i>
                                            @elseif($orderType =='appointment')
                                                <i class="fa fa-calendar-check fa-lg"></i>
                                            @endif
                                        </li>

                                        <li
                                                class="list-group-item border-0 bg-transparent px-1  d-flex justify-content-between align-items-center">
                                            <div class="ml-2">
                                                <p class="font-weight-bold">الوصف</p>
                                                <small>{{$order["desc"]}}</small>
                                            </div>
                                            <i class="fa fa-quote-left fa-lg"></i>

                                        </li>

                                        <li class="list-group-item border-0 bg-transparent px-1  d-flex justify-content-between align-items-center">
                                            <div id="coursePrice">
                                                @if($order["orderType"] =="course")
                                                    <p class="font-weight-bold mb-2">سعر الدورة</p>
                                                @else
                                                    <p class="font-weight-bold mb-2">سعر الجلسة</p>
                                                @endif

                                                <h6 class="text-danger font-weight-bold"
                                                    @if(session()->get('discountedPrice')) style="text-decoration: line-through" @endif>{{number_format($order["price"])}}
                                                    د.ع </h6>

                                                @if(session()->get('discountedPrice'))
                                                    <h6 class="mt-2 text-success font-weight-bold">{{number_format(session()->get('discountedPrice'))}}
                                                        د.ع</h6>

                                                @endif
                                            </div>
                                            <i class="fab fa-creative-commons-nc fa-lg"></i>

                                        </li>
                                        <li style="border-top: 1px solid #b8a747"></li>


                                        <li class="list-group-item border-0 bg-transparent px-1  d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="font-weight-bold mb-2">السعر الإجمالي</p>
                                                @if(session()->get('discountedPrice'))
                                                    <h4 class="text-danger font-weight-bold">
                                                        <span id="totalPrice">
                                                            {{number_format(session()->get('discountedPrice'))}} د.ع
                                                        </span>
                                                    </h4>

                                                @else
                                                    <h4 class="text-danger font-weight-bold">
                                                        <span id="totalPrice">{{number_format($order["price"])}}</span>
                                                        د.ع
                                                    </h4>
                                                @endif

                                            </div>


                                        </li>


                                    </ul>

                                    @if(!session()->get('discountedPrice') && $order["orderType"] !=='appointment')
                                        <div class="input-box" id="coupon_block">
                                            <p>هل لديك خصم؟</p>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="coupon-code"
                                                       placeholder="{{ __('Enter Your Coupon') }}"
                                                       aria-label="Coupon Code"
                                                       aria-describedby="coupon-btn">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-primary"
                                                            type="button"
                                                            id="coupon-btn">{{ __('Apply') }}</button>


                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12">
                            <form method="post" id="checkoutForm"
                                  action="{{route('payment.checkout.submit',['order_id'=>request('order_id'),'orderType'=>$orderType])}}">
                                @csrf

                                <input type="hidden" name="order_id" value="{{request('order_id')}}">
                                <input type="hidden" name="order_type" value="{{$orderType}}">

                                <div class="card rounded-lg shadow border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">طرق الدفع</h5>
                                        <p class="card-text mb-3">يمكن الدفع بإحدى وسائل الدفع الإلكتروني المتوفرة في
                                            الأسفل يرجى اختيار
                                            وسيلة
                                            الدفع المناسبة لك </p>

                                        <div class="form-row">

                                            @foreach($paymentMethods as $method)
                                                @php
                                                    $methodInfo = json_decode($method->information);
                                                @endphp
                                                <div class="form-group col-lg-4 col-md-6 col-6">
                                                    <input type="radio" name="payment_methods"
                                                           value="{{$method->keyword}}"
                                                           class="payment-method-radio form-control"
                                                           id="{{$method->keyword}}">
                                                    <label class="payment-method-label form-check-label rounded-lg"
                                                           for="{{$method->keyword}}">
                                                        <img style="width: 40px" src="{{asset($methodInfo->logo)}}"
                                                             class="m-auto rounded-lg"
                                                             alt="logo"/>
                                                        {{$methodInfo->label}}
                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>
                                        <div class="form-group mb-3" style="display: none" id="code_input">
                                            <label class="label" for="cash-code">كود الشراء</label>
                                            <input type="text" name="cash_code" class="form-control" id="cash-code"
                                                   placeholder="ادخل الكود الشراء هنا" aria-label="Coupon Code"
                                                   aria-describedby="coupon-btn">
                                        </div>

                                        <div class="form-group">
                                            <div class="mt-5 text-center">
                                                <span class="ajaxLoader" id="ajaxLoader"></span>
                                            </div>

                                        </div>


                                        <div class="form-group mt-5">
                                            <div class="text-center mb-3">
                                                <button class="btn btn-success px-4 py-2" type="submit"> إتمام
                                                    العملية
                                                </button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>


        </div>
    </section>

    <!--====== COURSES PART END ======-->

@endsection

@section('script')
    <script>
        $(document).ready(function () {

            //init variables
            let checkoutForm = $("#checkoutForm");
            let paymentMethodsSelect = $(".payment-method-radio");
            let ajaxLoader = $("#ajaxLoader");

            $("#coupon-btn").on("click", function () {
                let couponCode = $("#coupon-code").val();
                if (couponCode === '') {
                    toastr.error("ادخل كود الخصم");
                    return false;
                }

                $.ajax({
                    url: '/course-enrolment/apply-coupon',
                    cache: false,
                    method: "post",
                    data: {
                        coupon: couponCode,
                        id: "{{$order['course_id'] ?? $order["appointment_id"]}}"
                    },
                    beforeSend: function () {
                        ajaxLoader.css({'display': 'inline-block'});
                    },
                    complete: function (xhr, status) {
                        ajaxLoader.css({'display': 'none'});

                        let response = xhr.responseJSON;
                        console.log(status);
                        if (status === "error") {
                            toastr.error(response.error);
                        } else {
                            toastr.success(response.success);
                            $("#coupon_block").hide();
                            let newPrice = response.newPrice;
                            $("#coursePrice")
                                .append(`<h6 class="mt-2 text-success font-weight-bold">${newPrice.toLocaleString()} د.ع</h6>`);
                            $("#totalPrice").text(newPrice.toLocaleString() + " د.ع");
                            $("#coursePrice").find("h6").first().css('text-decoration', 'line-through');


                        }
                    }

                })
                return false;
            });


            $(checkoutForm).on("submit", function () {

                console.log(paymentMethodsSelect.val());
                if (paymentMethodsSelect.val() === '') {
                    toastr.error("اختر وسيلة دفع");
                    return false
                }


                $.ajax({
                    url: '/ajax/payment/checkout',
                    cache: false,
                    method: "post",
                    data: checkoutForm.serialize(),
                    beforeSend: function () {
                        ajaxLoader.css({'display': 'inline-block'});
                    },
                    complete: function (xhr, status) {
                        ajaxLoader.css({'display': 'none'});
                        let response = xhr.responseJSON;
                        if (status === "error") {
                            toastr.error(response.message);
                        } else {

                            if (response.data.url !== undefined) {
                                window.location.href = response.data.url;
                            }

                            console.log(response);


                        }
                    }

                })
                return false;
            });


            paymentMethodsSelect.on("change", function (event) {

                let selected = event.target.value;
                let code_input = $("#code_input");

                console.log(selected)
                if (selected === "cash") {
                    code_input.show();
                } else {
                    code_input.hide();
                }
            })


        });

    </script>

@endsection

@section("style")
    <style>
        .payment-method-radio {
            display: none;
        }

        .payment-method-label {
            border: 2px solid #D3DCDD;
            width: 150px;
            height: 150px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            text-align: center;
            justify-content: space-between;
            padding-bottom: 10px;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;

        }

        .payment-method-radio:hover + .payment-method-label {
            border-color: var(--primary);
        }

        .payment-method-radio:checked + .payment-method-label {
            border-color: var(--primary);
        }


        #ajaxLoader {
            width: 48px;
            height: 48px;
            position: relative;
            border: 3px solid;
            border-color: #568083 #0000 #256e77 #0000;
            border-radius: 50%;
            box-sizing: border-box;
            animation: 1s rotate linear infinite;
            display: none;
            margin: auto;
        }

        #ajaxLoader:before, #ajaxLoader:after {
            content: '';
            top: 0;
            left: 0;
            position: absolute;
            border: 10px solid transparent;
            border-bottom-color: #568083;
            transform: translate(-10px, 19px) rotate(-35deg);
        }

        #ajaxLoader:after {
            border-color: #709ba0 #0000 #0000 #0000;
            transform: translate(32px, 3px) rotate(-35deg);
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg)
            }
        }
    </style>
@endsection
