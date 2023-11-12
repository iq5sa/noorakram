<div class="main-header">
    <!-- Logo Header Start -->
    <div class="logo-header"
         data-background-color="white">

        <a href="{{route('index')}}" class="pt-2 text-dark" target="_blank">
            <h3>Admin dashboard</h3>
        </a>

        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                data-target="collapse"
                aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
        <i class="icon-menu"></i>
        </span>
        </button>


        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- Logo Header End -->

    <!-- Navbar Header Start -->
    <nav class="navbar navbar-header navbar-expand-lg"
         data-background-color="white2">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if (Auth::guard('admin')->user()->image != null)
                                <img src="{{ asset('/img/admins/' . Auth::guard('admin')->user()->image) }}"
                                     alt="Admin Image"
                                     class="avatar-img rounded-circle">
                            @else
                                <img src="{{ asset('/img/blank_user.jpg') }}" alt=""
                                     class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if (Auth::guard('admin')->user()->image != null)
                                            <img src="{{ asset('/img/admins/' . Auth::guard('admin')->user()->image) }}"
                                                 alt="Admin Image" class="avatar-img rounded-circle">
                                        @else
                                            <img src="{{ asset('/img/blank_user.jpg') }}" alt=""
                                                 class="avatar-img rounded-circle">
                                        @endif
                                    </div>

                                    <div class="u-text">
                                        <h4>
                                            {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}
                                        </h4>
                                        <p class="text-muted">{{ Auth::guard('admin')->user()->email }}</p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.edit_profile') }}">
                                    {{ __('Edit Profile') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.change_password') }}">
                                    {{ __('Change Password') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar Header End -->
</div>
