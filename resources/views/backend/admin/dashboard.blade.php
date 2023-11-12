@extends('backend.layout')

@section('content')
    <div class="mt-2 mb-4">
        <h2
                class="text-white pb-2">{{ __('Welcome back,') }} {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name . '!' }}</h2>
    </div>

    {{-- dashboard information start --}}
    @php
        if (!is_null($roleInfo)) {
          $rolePermissions = json_decode($roleInfo->permissions);
        }
    @endphp

    <div class="row dashboard-items">
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Course Management', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.course_management.courses', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-success card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-book"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Courses</p>
                                        <h4 class="card-title">{{ $totalCourses }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Course Management', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.course_management.categories', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-danger card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-sitemap"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Categories</p>
                                        <h4 class="card-title">{{ $totalCourseCategories }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Course Enrolments', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.course_enrolments') }}">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-users-class"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Courses enrollments</p>
                                        <h4 class="card-title">{{ $totalCourseEnrolments }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Instructors', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.instructors', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-warning card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-chalkboard-teacher"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Instructors</p>
                                        <h4 class="card-title">{{ $totalInstructors }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-blog"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Blogs</p>
                                        <h4 class="card-title">{{$totalBlog}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('admin.user_management.registered_users') }}">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="la flaticon-users"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Registered Users</p>
                                        <h4 class="card-title">{{ $totalRegisteredUsers }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Monthly Income ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Monthly Enrolments ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="enrolmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- dashboard information end --}}
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('/js/chart.min.js') }}"></script>

    <script>
        "use strict";
        const monthArr = @php echo json_encode($months) @endphp;
        const incomeArr = @php echo json_encode($incomes) @endphp;
        const enrolmentArr = @php echo json_encode($enrolments) @endphp;
    </script>

    <script type="text/javascript" src="{{ asset('/js/chart-init.js') }}"></script>
@endsection
