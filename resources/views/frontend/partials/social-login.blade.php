<div class="my-5 ">
    <a class="btn btn-outline-dark d-block mb-3" href="{{ route('user.login.google') }}"
       role="button"
       style="text-transform:none">
        <img width="20px" style="margin-bottom:3px; margin-right:5px"
             alt="Google sign-in"
             src="{{asset('img/google.svg')}}"/>
        تسجيل الدخول بواسطة جوجل
    </a>

    <a class="btn btn-outline-dark d-block mb-3"
       href="{{ route('user.login.facebook') }}"
       role="button"
       style="text-transform:none">
        <img width="20px" style="margin-bottom:3px; margin-right:5px"
             alt="Facebook sign-in"
             src="{{asset('img/facebook.svg')}}"/>
        تسجيل الدخول بواسطة فيسبوك
    </a>
</div>