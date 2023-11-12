@extends('frontend.layout')

@section('pageHeading')
    {{ __('Payment Success') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/asset/css/summernote-content.css') }}">
@endsection

@section('content')

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
                        <a href="{{route("user.my_course.curriculum",["id"=>$course->id])}}"
                           class="mt-4 btn btn-success">
                            <i class="fa fa-play-circle"></i>
                            اضغط
                            لمشاهدة
                            الدورة</a>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!--====== Purchase Success Section End ======-->
@endsection

@section('script')
    <script type="text/javascript">
        sessionStorage.removeItem('course_id');
        sessionStorage.removeItem('new_price');
    </script>
@endsection
