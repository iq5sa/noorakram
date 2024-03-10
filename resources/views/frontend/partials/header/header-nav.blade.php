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
                  <img data-src="{{asset('img/logo/logo250x250.webp')}}" class="lazy entered loaded"
                       alt="logo" style="width: 80px" data-ll-status="loaded"
                       src="{{asset('img/logo/logo250x250.webp')}}">
                </a>
              </li>

              <li class="menu-item">
                <a href="{{route('index')}}">الصفحة الرئيسية</a>
              </li>

              <li class="menu-item">
                <a href="{{route('courses')}}">الدورات</a>
              </li>
              <li class="menu-item">
                <a href="{{route('courses')}}?type=onsite">الدورات الحضورية</a>
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
                 href="#">
                <i class="fal fa-search "></i>
              </a>
            </li>

            <li class="fa-li">
              <a
                href="{{route("courses")}}">
                <i class="fal fa-shopping-cart "></i>
              </a>
            </li>

            @if(auth()->check())

              <li class="fa-li">
                <div class="dropdown" href="">
                  <a href="" class="dropdown-toggle " data-toggle="dropdown"
                     aria-expanded="false">
                    <img src="{{asset("img/user.png")}}" style=" width: 45px;
      height: 45px;
      vertical-align: middle;" class="img-fluid rounded-pill" alt="user-profile"/>
                  </a>
                  <div class="dropdown-menu border-0 shadow-lg" style="">
                    <div class="dropdown-item text-center">
                      <a href="{{route('user.dashboard')}}">الملف الشخصي</a>
                    </div>
                    <div class="dropdown-item text-center ">
                      <a href="{{route('user.purchase_history')}}">سجل المشتريات</a>
                    </div>
                    <div class="dropdown-item text-center ">
                      <a href="{{route('user.my_courses')}}">الدورات</a>
                    </div>

                    <div class="dropdown-item text-center">
                      <a class="text-danger" href="{{route('user.logout')}}">تسجيل الخروج</a>
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


@section("style")
  <style>

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
