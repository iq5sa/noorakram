<div class="col-lg-3">
    <div class="user-sidebar">
        <ul class="links">
            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    الحساب الشخصي
                </a>
            </li>

            <li>
                <a href="{{ route('user.my_courses') }}"
                   class="{{ request()->routeIs('user.my_courses') ? 'active' : '' }}">
                    دوراتي
                </a>
            </li>

            <li>
                <a href="{{ route('user.purchase_history') }}" target="_blank">
                    سجل المشتريات
                </a>
            </li>

            <li>
                <a href="{{ route('user.edit_profile') }}"
                   class="{{ request()->routeIs('user.edit_profile') ? 'active' : '' }}">
                    {{ __('Edit Profile') }}
                </a>
            </li>

            <li>
                <a href="{{ route('user.change_password') }}"
                   class="{{ request()->routeIs('user.change_password') ? 'active' : '' }}">
                    {{ __('Change Password') }}
                </a>
            </li>

            <li>
                <a href="{{ route('user.logout') }}">
                    {{ __('Logout') }}
                </a>
            </li>
        </ul>
    </div>
</div>

