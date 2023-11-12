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
                <div class="col-lg-12 col-md-8 col-sm-12">

                    <div class="row">
                        <div class="col-md-5">
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
                                            <div>
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
                                            <div>
                                                <p class="font-weight-bold">الصف</p>
                                                <small>{{$order["desc"]}}</small>
                                            </div>
                                            <i class="fa fa-quote-left fa-lg"></i>

                                        </li>


                                        @if(!empty($order["discount"]))
                                            <li
                                                    class="list-group-item border-0 bg-transparent px-1  d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="font-weight-bold mb-2">السعر السابق</p>
                                                    <h4 class="text-danger font-weight-bold">
                                                        <del>{{number_format($order["discount"])}} د.ع</del>
                                                    </h4>
                                                </div>
                                                <i class="fab fa-creative-commons-nc fa-lg"></i>

                                            </li>

                                        @endif
                                        <li
                                                class="list-group-item border-0 bg-transparent px-1  d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="font-weight-bold mb-2">السعر الإجمالي</p>
                                                <h4 class="text-danger font-weight-bold ">{{number_format($order["price"])}}
                                                    د.ع </h4>
                                            </div>
                                            <i class="fa fa-dollar-sign fa-lg"></i>

                                        </li>
                                    </ul>
                                    <p>هل لديك خصم؟</p>
                                    <div class="input-box">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="coupon-code"
                                                   placeholder="{{ __('Enter Your Coupon') }}" aria-label="Coupon Code"
                                                   aria-describedby="coupon-btn">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-primary"
                                                        type="button"
                                                        id="coupon-btn">{{ __('Apply') }}</button>


                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <form method="post"
                                  action="{{route('payment.checkout.submit',['order_id'=>request('order_id'),'$orderType'=>$orderType])}}">
                                @csrf

                                <div class="card rounded-lg shadow border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">طرق الدفع</h5>
                                        <p class="card-text mb-3">يمكن الدفع بإحدى وسائل الدفع الإلكتروني المتوفرة في
                                            الأسفل يرجى اختيار
                                            وسيلة
                                            الدفع المناسبة لك </p>

                                        <div class="row mb-5">


                                            @foreach($paymentMethods as $method)
                                                @php
                                                    $methodInfo = json_decode($method->information);
                                                @endphp
                                                <div class="col mb-lg-0 mb-md-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" name="payment_method"
                                                               value="{{$method->keyword}}"
                                                               class="payment-method-radio form-check-input"
                                                               id="{{$method->keyword}}">
                                                        <label class="payment-method-label form-check-label rounded-lg"
                                                               for="{{$method->keyword}}">
                                                            <img src="{{asset($methodInfo->logo)}}"
                                                                 class="w-50 m-auto rounded-lg"
                                                                 alt="logo"/>
                                                            {{$methodInfo->label}}
                                                        </label>


                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="form-group mb-3" style="display: none" id="code_input">
                                            <label class="label" for="cash-code">كود الشراء</label>
                                            <input type="text" name="cash_code" class="form-control" id="cash-code"
                                                   placeholder="ادخل الكود الشراء هنا" aria-label="Coupon Code"
                                                   aria-describedby="coupon-btn">
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

            let paymentMethodsSelect = $(".payment-method-radio");
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
    </style>
@endsection
