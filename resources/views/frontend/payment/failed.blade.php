@extends('frontend.layout')

@section('pageHeading')
    {{ __('Payment Success') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/asset/css/summernote-content.css') }}">
@endsection

@section('content')

    @if ($order->depositAmount !=="0" && $order->ErrorCode =="0")
        <div class="purchase-message">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="purchase-success">
                            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
                            <h2>{{ __('Success') . '!' }}</h2>
                            <p>{{ __('Your transaction was successful') . '.' }}</p>
                            <p>{{ __('We have sent you a mail with an invoice') . '.' }}</p>
                            <p class="mt-4">{{ __('Thank you') . '.' }}</p>
                            <a href="#" class="mt-4">اضغط لمشاهدة الدورة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="purchase-message">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="purchase-success">
                            <div class="icon text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                            <h2>تم رفض العملية</h2>
                            <p>
                                <span> حدثت مشكلة أثناء معالجة طلبك. إليك بعض الأمور التي يمكنك التحقق منها والمحاولة مرة أخرى</span>
                            <ul>
                                <li>تأكد من إدخال معلومات الدفع (رَقَم بطاقة الائتمان، تاريخ انتهاء الصَّلاحِيَة، CVV،
                                    إلخ) بشكل صحيح.
                                </li>
                                <li>تأكد من توفر أموال كافية في حسابك أو أن بطاقتك الائتمانية ليست منتهية
                                    الصَّلاحِيَة.
                                </li>
                                <li>تحقق مرة أخرى من عنوان إرسال الفواتير الذي قدمته ليطابق معلومات الفواتير الخاصة
                                    بطريقة الدفع الخاصة
                                    بك.
                                </li>
                                <li>إذا استمرت المشكلة، فقد تحتاج إلى الاتصال بالبنك أو جهة إصدار بطاقتك الائتمانية
                                    للتأكد من عدم وجود
                                    مشكلات من جانبهم.
                                </li>
                                <li>يمكنك أيضًا تجرِبة استخدام طريقة دفع مختلفة إذا كانت متوفرة.</li>
                            </ul>
                            </p>

                            <a href="{{route("order.pending.pay",["id"=>$order->OrderNumber])}}" class="mt-4">اعد
                                المحاولة</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--====== Purchase Success Section End ======-->
@endsection

@section('script')
    <script type="text/javascript">
        sessionStorage.removeItem('course_id');
        sessionStorage.removeItem('new_price');
    </script>
@endsection
