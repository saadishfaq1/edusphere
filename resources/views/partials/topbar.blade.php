<header id="page-topbar">
    <div class="navbar-header" style="display:flex !important; align-items:center; justify-content:space-between; width:100%; box-sizing:border-box;">

        <!-- LEFT: brand + school name -->
        <div class="navbar-left-group" style="display:flex; align-items:center; flex:1 1 auto; min-width:0;">
            <div class="navbar-brand-box" style="flex-shrink:0;">
                <a href="{{ route('tenant.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-dark-sm.png') }}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark-sm.png') }}" alt="" height="26">
                    </span>
                </a>
                <a href="{{ route('tenant.dashboard') }}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-light-sm.png') }}" alt="" height="26">
                    </span>
                </a>
            </div>

            <div class="page-title-box" style="margin-left:16px; overflow:hidden; white-space:nowrap; min-width:0;">
                <h4 class="page-title mb-0" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin:0;">{{ app('tenant')->name }}</h4>
            </div>
        </div>

        <!-- RIGHT: search + profile, always pinned right -->
        <div class="navbar-right-group" style="display:flex !important; align-items:center; flex-shrink:0; margin-left:auto !important;">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon" data-bs-toggle="dropdown">
                    <i class="bx bx-search icon-sm align-middle"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <form class="p-2">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" class="form-control rounded bg-light border-0" placeholder="Search...">
                                <i class="bx bx-search search-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" data-bs-toggle="dropdown">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="Avatar">
                    <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{ auth()->user()->name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                        <p class="mb-0 font-size-11 text-muted">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i>
                            <span class="align-middle">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="topnav" style="display:none;"></div>
        <div class="ishorizontal-topbar" style="display:none;"></div>
    </div>
</header>

<script>
// Runtime fallback: forces the layout even if any cached/external CSS fights it.
(function () {
    function forceTopbarLayout() {
        var header = document.querySelector('#page-topbar .navbar-header');
        var right = document.querySelector('#page-topbar .navbar-right-group');
        if (header) {
            header.style.setProperty('display', 'flex', 'important');
            header.style.setProperty('justify-content', 'space-between', 'important');
            header.style.setProperty('width', '100%', 'important');
        }
        if (right) {
            right.style.setProperty('margin-left', 'auto', 'important');
            right.style.setProperty('display', 'flex', 'important');
        }
    }
    forceTopbarLayout();
    document.addEventListener('DOMContentLoaded', forceTopbarLayout);
    window.addEventListener('load', forceTopbarLayout);
})();
</script>

