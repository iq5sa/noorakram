@php use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Route;use Illuminate\Support\Str; @endphp
<div class="container-fluid">
    <div class="header-navigation">
        <div class="site-menu d-flex align-items-center justify-content-between">
            <div class="primary-menu">
                <div class="nav-menu">
                    <!-- Navbar Close Icon -->
                    <div class="navbar-close">
                        <div class="cross-wrap"><i class="far fa-times"></i></div>
                    </div>

                    <!-- Nav Menu -->
                    <nav class="main-menu">
                        <ul>
                            <li class="menu-item">
                                <a href="{{route("index")}}">
                                    <img data-src="{{asset('img/logo/logo.png')}}" class="lazy entered loaded"
                                         alt="logo" style="width: 80px" data-ll-status="loaded"
                                         src="{{asset('img/logo/logo.png')}}">
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('index')}}">الصفحة الرئيسية</a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('courses')}}">الدورات</a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('blogs')}}">المقالات</a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('home.aboutPage')}}">من نحن</a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('contact')}}">تواصل معنا</a>
                            </li>

                        </ul>
                    </nav>
                </div>

                <!-- Navbar Toggler -->
                <div class="navbar-toggler">
                    <span></span><span></span><span></span>
                </div>
            </div>

            <div class="navbar-item">
                <div class="menu-icon">
                    <ul class="fa-ul">
                        <li class="fa-li">
                            <a data-toggle="modal" data-target="#searchPopup"
                               href="{{route("user.signup")}}">
                                <i class="fal fa-search "></i>
                            </a>
                        </li>

                        <li class="fa-li">
                            <a
                                    href="{{route("courses")}}">
                                <i class="fal fa-shopping-cart "></i>
                            </a>
                        </li>

                        @if(Auth::check())

                            <li class="fa-li">
                                <div class="dropdown" href="">
                                    <a href="" class="dropdown-toggle user-avatar" data-toggle="dropdown"
                                       aria-expanded="false">
                                        @php
                                            $firstName = Auth::user()->first_name;
                                            $letter = Str::substr($firstName,0,1);
                                        @endphp
                                        {{Str::upper($letter)}}
                                    </a>
                                    <div class="dropdown-menu border-0 shadow-lg">
                                        <div class="dropdown-item text-center">
                                            <a href="{{\route('user.dashboard')}}">الملف الشخصي</a>
                                        </div>
                                        <div class="dropdown-item text-center ">
                                            <a href="{{\route('user.purchase_history')}}">سجل المشتريات</a>
                                        </div>
                                        <div class="dropdown-item text-center ">
                                            <a href="{{\route('user.my_courses')}}">الدورات</a>
                                        </div>

                                        <div class="dropdown-item text-center">
                                            <a class="text-danger" href="{{\route('user.logout')}}">تسجيل الخروج</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        @else
                            <li class="fa-li">
                                <a
                                        href="{{route("user.signup")}}">
                                    <i class="fal fa-user "></i>
                                </a>
                            </li>
                        @endif
                    </ul>

                </div>

            </div>
        </div>
    </div>
</div>

{{--<div class="container-fluid">--}}
{{--  <div class="header-navigation">--}}
{{--    <div class="site-menu d-flex align-items-center justify-content-between">--}}
{{--      <div class="primary-menu">--}}
{{--        <div class="nav-menu">--}}
{{--          @include("frontend.partials.search-popup")--}}
{{--          <!-- Navbar Close Icon -->--}}
{{--          <div class="navbar-close">--}}
{{--            <div class="cross-wrap"><i class="far fa-times"></i></div>--}}
{{--          </div>--}}

{{--          <!-- Nav Menu -->--}}
{{--          <nav class="main-menu">--}}

{{--            <ul>--}}
{{--              <li class="menu-item ">--}}
{{--                <div class="logo d-none d-md-block d-lg-block">--}}
{{--                  <a href="http://127.0.0.1:8000">--}}
{{--                    <img data-src="{{asset('img/logo.png')}}" class="lazy entered loaded"--}}
{{--                         alt="website logo" style="width: 80px" data-ll-status="loaded"--}}
{{--                         src="{{asset('img/logo.png')}}">--}}
{{--                  </a>--}}
{{--                </div>--}}
{{--              </li>--}}


{{--              <li class="menu-item">--}}
{{--                <a href="{{route('index')}}">الصفحة الرئيسية</a>--}}
{{--              </li>--}}

{{--              <li class="menu-item">--}}
{{--                <a href="{{route('courses')}}">الدورات</a>--}}
{{--              </li>--}}

{{--              <li class="menu-item">--}}
{{--                <a href="{{route('blogs')}}">المقالات</a>--}}
{{--              </li>--}}

{{--              <li class="menu-item">--}}
{{--                <a href="{{route('home.aboutPage')}}">من نحن</a>--}}
{{--              </li>--}}

{{--              <li class="menu-item">--}}
{{--                <a href="{{route('contact')}}">تواصل معنا</a>--}}
{{--              </li>--}}

{{--              <li class="menu-item ">--}}
{{--                <div class="logo d-block d-sm-none">--}}
{{--                  <a href="{{route('index')}}" class="text-center">--}}
{{--                    <img data-src="{{asset('img/logo.png')}}" class="lazy"--}}
{{--                         alt="website logo"--}}
{{--                         style="width: 80px">--}}
{{--                  </a>--}}
{{--                </div>--}}
{{--              </li>--}}
{{--            </ul>--}}
{{--          </nav>--}}
{{--        </div>--}}

{{--        <!-- Navbar Toggler -->--}}
{{--        <div class="navbar-toggler">--}}
{{--          <span></span><span></span><span></span>--}}
{{--        </div>--}}

{{--      </div>--}}

{{--      --}}{{--      <div class="d-lg-none d-flex align-items-center justify-content-center">--}}
{{--      --}}{{--        <a href="{{route('index')}}" title="الموقع الرسمي">--}}
{{--      --}}{{--          <h4 class="text-secondary">أكاديمية نور اكرم</h4>--}}
{{--      --}}{{--        </a>--}}

{{--    </div>--}}
{{--    <div class="navbar-item d-flex align-items-center justify-content-end">--}}

{{--      <div class="menu-icon">--}}
{{--        <ul>--}}
{{--          @auth("web")--}}
{{--            <li class="d-none d-md-inline-block ">--}}
{{--              <a href="{{route("user.dashboard")}}" class="px-3 shadow-sm py-2 rounded-lg"--}}
{{--                 data-toggle="modal">--}}
{{--                <i class="fa fa-user-circle"></i>--}}
{{--              </a>--}}
{{--            </li>--}}

{{--          @endauth--}}

{{--          @guest("web")--}}
{{--            <li class="mx-0 d-none d-md-inline-block">--}}
{{--              <a class="btn bg-primary rounded-lg"--}}
{{--                 href="{{route("user.signup")}}">--}}
{{--                تسجيل--}}
{{--              </a>--}}
{{--            </li>--}}
{{--          @endguest--}}

{{--          <li class="">--}}
{{--            <a href="#" class="mx-0 mx-md-3 px-3  py-2 rounded-lg" data-toggle="modal"--}}
{{--               data-target="#searchPopup">--}}
{{--              <i class="fa fa-search" id="course-search-icon"></i>--}}
{{--            </a>--}}
{{--          </li>--}}

{{--          <li class="d-none d-md-inline-block">--}}
{{--            <a href="{{route("courses")}}" class="px-3 py-2 rounded-lg"--}}
{{--               title="تصفح الدورات">--}}
{{--              <i class="fa fa-cart-plus" id="course-search-icon"></i>--}}
{{--            </a>--}}
{{--          </li>--}}

{{--        </ul>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--</div>--}}
{{--</div>--}}


@section("style")
    <style>
        .user-avatar {
            width: 45px;
            height: 45px;
            border: 1px solid #eee;
            background: var(--primary);
            padding: 2px;
            border-radius: 50%;
        }

        .header-searchBar i {
            color: var(--primary);
            right: 54px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            cursor: pointer;
        }
    </style>

@endsection


@section("script")

    <script>
        // $(document).ready(function () {
        //   $('#searchPopup').modal();
        // })
    </script>
@endsection
