<div class="modal fade" id="loginHintModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="loginHintModalLabel">هل تريد تسجيل الدخول؟</h5>
                <button type="button" class="close text-white rounded-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <p class="mt-3 mb-3">
                    سجل الدخول لتتمكن من حجز موعد بشكل أسرع وملئ المعلومات الشخصية مرة واحدة فقط.
                </p>
                <a class="btn btn-primary mt-3 mb-3" href="{{route('user.login')}}">تسجيل
                    الدخول</a>
                <p>
                    ليس لديك حساب؟
                </p>
                <a class="btn btn-secondary mt-3 mb-3" href="{{route('user.signup')}}">انشاء حساب
                    جديد</a>
            </div>

        </div>
    </div>
</div>

@section("script")
    @parent
    <script>
        $(document).ready(function () {
            $("#loginHintModal").modal("show");
        });
    </script>

@endsection
