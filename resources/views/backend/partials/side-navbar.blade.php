<div class="sidebar sidebar-style-2"
     data-background-color="white">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('admin')->user()->image != null)
                        <img src="{{ asset('/img/admins/' . Auth::guard('admin')->user()->image) }}"
                             alt="Admin Image"
                             class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('/img/blank_user.jpg') }}" alt="" class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}

                @if (is_null($roleInfo))
                    <span class="user-level">{{ __('Super Admin') }}</span>
                @else
                    <span class="user-level">{{ $roleInfo->name }}</span>
                @endif

              <span class="caret"></span>
            </span>
                    </a>

                    <div class="clearfix"></div>

                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('admin.edit_profile') }}">
                                    <span class="link-collapse">Edit profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.change_password') }}">
                                    <span class="link-collapse">Change password</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.logout') }}">
                                    <span class="link-collapse">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @php
                if (!is_null($roleInfo)) {
                  $rolePermissions = json_decode($roleInfo->permissions);
                }
            @endphp

            <ul class="nav nav-primary">
                {{-- search --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="">
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                       placeholder="Search Menu Here...">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- dashboard --}}
                <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- instructor --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Instructors', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.instructors')) active
            @elseif (request()->routeIs('admin.create_instructor')) active
            @elseif (request()->routeIs('admin.edit_instructor')) active
            @elseif (request()->routeIs('admin.instructor.social_links')) active
            @elseif (request()->routeIs('admin.instructor.edit_social_link')) active @endif"
                    >
                        <a href="{{ route('admin.instructors', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-chalkboard-teacher"></i>
                            <p>Instructors</p>
                        </a>
                    </li>
                @endif

                {{-- course --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Course Management', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.course_management.categories')) active
            @elseif (request()->routeIs('admin.course_management.courses')) active
            @elseif (request()->routeIs('admin.course_management.create_course')) active
            @elseif (request()->routeIs('admin.course_management.edit_course')) active
            @elseif (request()->routeIs('admin.course_management.course.faqs')) active
            @elseif (request()->routeIs('admin.course_management.course.thanks_page')) active
            @elseif (request()->routeIs('admin.course_management.course.certificate_settings')) active
            @elseif (request()->routeIs('admin.course_management.course.modules')) active
            @elseif (request()->routeIs('admin.course_management.lesson.contents')) active
            @elseif (request()->routeIs('admin.course_management.lesson.create_quiz')) active
            @elseif (request()->routeIs('admin.course_management.lesson.manage_quiz')) active
            @elseif (request()->routeIs('admin.course_management.lesson.edit_quiz')) active
            @elseif (request()->routeIs('admin.course_management.coupons')) active @endif"
                    >
                        <a data-toggle="collapse" href="#course">
                            <i class="fal fa-book"></i>
                            <p>{{ __('Courses Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="course" class="collapse
              @if (request()->routeIs('admin.course_management.categories')) show
              @elseif (request()->routeIs('admin.course_management.courses')) show
              @elseif (request()->routeIs('admin.course_management.create_course')) show
              @elseif (request()->routeIs('admin.course_management.edit_course')) show
              @elseif (request()->routeIs('admin.course_management.course.faqs')) show
              @elseif (request()->routeIs('admin.course_management.course.thanks_page')) show
              @elseif (request()->routeIs('admin.course_management.course.certificate_settings')) show
              @elseif (request()->routeIs('admin.course_management.course.modules')) show
              @elseif (request()->routeIs('admin.course_management.lesson.contents')) show
              @elseif (request()->routeIs('admin.course_management.lesson.create_quiz')) show
              @elseif (request()->routeIs('admin.course_management.lesson.manage_quiz')) show
              @elseif (request()->routeIs('admin.course_management.lesson.edit_quiz')) show
              @elseif (request()->routeIs('admin.course_management.coupons')) show @endif"
                        >
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.course_management.categories') ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_management.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">Categories</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.course_management.coupons') ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_management.coupons') }}">
                                        <span class="sub-item">Coupons</span>
                                    </a>
                                </li>

                                <li class="@if (request()->routeIs('admin.course_management.courses')) active
                  @elseif (request()->routeIs('admin.course_management.create_course')) active
                  @elseif (request()->routeIs('admin.course_management.edit_course')) active
                  @elseif (request()->routeIs('admin.course_management.course.faqs')) active
                  @elseif (request()->routeIs('admin.course_management.course.thanks_page')) active
                  @elseif (request()->routeIs('admin.course_management.course.certificate_settings')) active
                  @elseif (request()->routeIs('admin.course_management.course.modules')) active
                  @elseif (request()->routeIs('admin.course_management.lesson.contents')) active
                  @elseif (request()->routeIs('admin.course_management.lesson.create_quiz')) active
                  @elseif (request()->routeIs('admin.course_management.lesson.manage_quiz')) active
                  @elseif (request()->routeIs('admin.course_management.lesson.edit_quiz')) active @endif"
                                >
                                    <a href="{{ route('admin.course_management.courses', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">Courses</span>
                                    </a>
                                </li>


                                <li class="{{ request()->routeIs('admin.course_management.coupons') ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_management.cashCodes') }}">
                                        <span class="sub-item">Cash codes</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- course enrolments --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Course Enrolments', $rolePermissions)))
                    <li class="nav-item
          @if (request()->routeIs('admin.course_enrolments')) active
          @elseif (request()->routeIs('admin.course_enrolment.details')) active
          @elseif (request()->routeIs('admin.course_enrolments.report')) active @endif"
                    >
                        <a data-toggle="collapse" href="#enrolment">
                            <i class="fal fa-users-class"></i>
                            <p>{{ __('Course Enrolments') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="enrolment" class="collapse
            @if (request()->routeIs('admin.course_enrolments')) show
            @elseif (request()->routeIs('admin.course_enrolment.details')) show
            @elseif (request()->routeIs('admin.course_enrolments.report')) show @endif"
                        >
                            <ul class="nav nav-collapse">
                                <li
                                        class="{{ request()->routeIs('admin.course_enrolments') && empty(request()->input('status')) ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_enrolments') }}">
                                        <span class="sub-item">{{ __('All Enrolments') }}</span>
                                    </a>
                                </li>

                                <li
                                        class="{{ request()->routeIs('admin.course_enrolments') && request()->input('status') == 'completed' ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_enrolments', ['status' => 'completed']) }}">
                                        <span class="sub-item">{{ __('Completed Enrolments') }}</span>
                                    </a>
                                </li>

                                <li
                                        class="{{ request()->routeIs('admin.course_enrolments') && request()->input('status') == 'pending' ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_enrolments', ['status' => 'pending']) }}">
                                        <span class="sub-item">{{ __('Pending Enrolments') }}</span>
                                    </a>
                                </li>

                                <li
                                        class="{{ request()->routeIs('admin.course_enrolments') && request()->input('status') == 'rejected' ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_enrolments', ['status' => 'rejected']) }}">
                                        <span class="sub-item">{{ __('Rejected Enrolments') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.course_enrolments.report') ? 'active' : '' }}">
                                    <a href="{{ route('admin.course_enrolments.report') }}">
                                        <span class="sub-item">{{ __('Report') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- user --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.user_management.registered_users')) active
            @elseif (request()->routeIs('admin.user_management.user_details')) active
            @elseif (request()->routeIs('admin.user_management.user.change_password')) active
            @elseif (request()->routeIs('admin.user_management.subscribers')) active
            @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active
            @elseif (request()->routeIs('admin.user_management.push_notification.settings')) active
            @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif"
                    >
                        <a data-toggle="collapse" href="#user">
                            <i class="la flaticon-users"></i>
                            <p>{{ __('User Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="user" class="collapse
              @if (request()->routeIs('admin.user_management.registered_users')) show
              @elseif (request()->routeIs('admin.user_management.user_details')) show
              @elseif (request()->routeIs('admin.user_management.user.change_password')) show
              @elseif (request()->routeIs('admin.user_management.subscribers')) show
              @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) show
              @elseif (request()->routeIs('admin.user_management.push_notification.settings')) show
              @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif"
                        >
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.user_management.registered_users')) active
                  @elseif (request()->routeIs('admin.user_management.user_details')) active
                  @elseif (request()->routeIs('admin.user_management.user.change_password')) active @endif"
                                >
                                    <a href="{{ route('admin.user_management.registered_users') }}">
                                        <span class="sub-item">{{ __('Registered Users') }}</span>
                                    </a>
                                </li>

                                <li class="@if (request()->routeIs('admin.user_management.subscribers')) active
                  @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif"
                                >
                                    <a href="{{ route('admin.user_management.subscribers') }}">
                                        <span class="sub-item">{{ __('Subscribers') }}</span>
                                    </a>
                                </li>

                                <li class="submenu">
                                    <a data-toggle="collapse" href="#push_notification">
                                        <span class="sub-item">{{ __('Push Notification') }}</span>
                                        <span class="caret"></span>
                                    </a>

                                    <div id="push_notification" class="collapse
                    @if (request()->routeIs('admin.user_management.push_notification.settings')) show
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif"
                                    >
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                    class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                                                <a href="{{ route('admin.user_management.push_notification.settings') }}">
                                                    <span class="sub-item">{{ __('Settings') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                    class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                                                <a href="{{ route('admin.user_management.push_notification.notification_for_visitors') }}">
                                                    <span class="sub-item">{{ __('Send Notification') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- home page --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Home Page', $rolePermissions)))
                    <li class="nav-item
            @if (request()->routeIs('admin.home_page.section_titles')) active
            @elseif (request()->routeIs('admin.home_page.action_section')) active
            @elseif (request()->routeIs('admin.home_page.features_section')) active
            @elseif (request()->routeIs('admin.home_page.testimonials_section')) active
            @elseif (request()->routeIs('admin.home_page.newsletter_section')) active
            @elseif (request()->routeIs('admin.home_page.section_customization')) active @endif"
                    >
                        <a data-toggle="collapse" href="#home_page">
                            <i class="fal fa-layer-group"></i>
                            <p>{{ __('Home Page') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="home_page" class="collapse
              
              @if (request()->routeIs('admin.home_page.section_titles')) show
              @elseif (request()->routeIs('admin.home_page.action_section')) show
              @elseif (request()->routeIs('admin.home_page.features_section')) show
              @elseif (request()->routeIs('admin.home_page.video_section')) show
              @elseif (request()->routeIs('admin.home_page.fun_facts_section')) show
              @elseif (request()->routeIs('admin.home_page.testimonials_section')) show
              @elseif (request()->routeIs('admin.home_page.newsletter_section')) show
              @elseif (request()->routeIs('admin.home_page.course_categories_section')) show
              @elseif (request()->routeIs('admin.home_page.section_customization')) show @endif"
                        >
                            <ul class="nav nav-collapse">


                            </ul>
                        </div>
                    </li>
                @endif

                {{-- blog --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.blog_management.categories')) active
            @elseif (request()->routeIs('admin.blog_management.blogs')) active
            @elseif (request()->routeIs('admin.blog_management.create_blog')) active
            @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif"
                    >
                        <a data-toggle="collapse" href="#blog">
                            <i class="fal fa-blog"></i>
                            <p>{{ __('Blog Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="blog" class="collapse
              @if (request()->routeIs('admin.blog_management.categories')) show
              @elseif (request()->routeIs('admin.blog_management.blogs')) show
              @elseif (request()->routeIs('admin.blog_management.create_blog')) show
              @elseif (request()->routeIs('admin.blog_management.edit_blog')) show @endif"
                        >
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.blog_management.categories') ? 'active' : '' }}">
                                    <a href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Categories') }}</span>
                                    </a>
                                </li>

                                <li class="@if (request()->routeIs('admin.blog_management.blogs')) active
                  @elseif (request()->routeIs('admin.blog_management.create_blog')) active
                  @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif"
                                >
                                    <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Blog') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- faq --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('FAQ Management', $rolePermissions)))
                    <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
                        <a href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-round"></i>
                            <p>{{ __('FAQ Management') }}</p>
                        </a>
                    </li>
                @endif

                {{-- announcement popup --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Announcement Popups', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.announcement_popups')) active
            @elseif (request()->routeIs('admin.announcement_popups.select_popup_type')) active
            @elseif (request()->routeIs('admin.announcement_popups.create_popup')) active
            @elseif (request()->routeIs('admin.announcement_popups.edit_popup')) active @endif"
                    >
                        <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-bullhorn"></i>
                            <p>{{ __('Announcement Popups') }}</p>
                        </a>
                    </li>
                @endif

                {{-- basic settings --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Basic Settings', $rolePermissions)))
                    <li class="nav-item

                        @if (request()->routeIs('admin.basic_settings.maintenance_mode')) active
                        @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) active
                        @elseif (request()->routeIs('admin.basic_settings.social_medias')) active @endif">
                        <a data-toggle="collapse" href="#basic_settings">
                            <i class="la flaticon-settings"></i>
                            <p>{{ __('Basic Settings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="basic_settings" class="collapse

              @if (request()->routeIs('admin.basic_settings.maintenance_mode')) show
              @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) show
              @elseif (request()->routeIs('admin.basic_settings.social_medias')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                                        <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif

                {{-- admin --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Admin Management', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active
            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active
            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif"
                    >
                        <a data-toggle="collapse" href="#admin">
                            <i class="fal fa-users-cog"></i>
                            <p>{{ __('Admin Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="admin" class="collapse
              @if (request()->routeIs('admin.admin_management.role_permissions')) show
              @elseif (request()->routeIs('admin.admin_management.role.permissions')) show
              @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif"
                        >
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.admin_management.role_permissions')) active
                  @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif"
                                >
                                    <a href="{{ route('admin.admin_management.role_permissions') }}">
                                        <span class="sub-item">{{ __('Role & Permissions') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                                    <a href="{{ route('admin.admin_management.registered_admins') }}">
                                        <span class="sub-item">{{ __('Registered Admins') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- language --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Language Management', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.language_management')) active
            @elseif (request()->routeIs('admin.language_management.edit_keyword')) active @endif"
                    >
                        <a href="{{ route('admin.language_management') }}">
                            <i class="fal fa-language"></i>
                            <p>{{ __('Language Management') }}</p>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
