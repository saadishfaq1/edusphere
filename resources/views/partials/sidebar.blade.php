<!-- SIDEBAR-TEST-MARKER-999 -->
<div class="vertical-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('tenant.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-dark-sm.png') }}" alt="" height="26"></span>
            <span class="logo-lg"><img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="28"></span>
        </a>
        <a href="{{ route('tenant.dashboard') }}" class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="30"></span>
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-light-sm.png') }}" alt="" height="26"></span>
        </a>
    </div>

    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Main</li>
                <li>
                    <a href="{{ route('tenant.dashboard') }}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">School</li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-user icon nav-icon"></i>
                        <span class="menu-item">Students</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('students.index') }}">All Students</a></li>
                        <li><a href="{{ route('students.create') }}">Add Student</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-book-open icon nav-icon"></i>
                        <span class="menu-item">Academics</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('classes.index') }}">Classes</a></li>
                        <li><a href="{{ route('sections.index') }}">Sections</a></li>
                        <li><a href="{{ route('timetable.index') }}">Timetable</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item">Attendance</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('attendance.create') }}">Mark Attendance</a></li>
                        <li><a href="{{ route('attendance.report') }}">Attendance Report</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-file icon nav-icon"></i>
                        <span class="menu-item">Examinations</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('subjects.index') }}">Subjects</a></li>
                        <li><a href="{{ route('examinations.index') }}">Exams</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-money icon nav-icon"></i>
                        <span class="menu-item">Fees & Finance</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('fees.categories') }}">Fee Categories</a></li>
                        <li><a href="{{ route('fees.structures') }}">Fee Structure</a></li>
                        <li><a href="{{ route('fees.settings') }}">School & Bank Settings</a></li>
                        <li><a href="{{ route('fees.scholarships') }}">Scholarships</a></li>
                        <li><a href="{{ route('fees.invoices') }}">Invoices</a></li>
                        <li><a href="{{ route('finance.income') }}">Income</a></li>
                        <li><a href="{{ route('finance.expenses') }}">Expenses</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-briefcase icon nav-icon"></i>
                        <span class="menu-item">HR / Staff</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('hr.employees') }}">Employees</a></li>
                        <li><a href="{{ route('hr.leaves') }}">Leave Requests</a></li>
                        <li><a href="{{ route('hr.payroll') }}">Payroll</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-library icon nav-icon"></i>
                        <span class="menu-item">Library</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('library.books') }}">Books</a></li>
                        <li><a href="{{ route('library.issues') }}">Issue / Return</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-bus icon nav-icon"></i>
                        <span class="menu-item">Transport</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('transport.vehicles') }}">Vehicles</a></li>
                        <li><a href="{{ route('transport.routes') }}">Routes</a></li>
                        <li><a href="{{ route('transport.assignments') }}">Student Assignments</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bx-bell icon nav-icon"></i>
                        <span class="menu-item">Communication</span>
                    </a>
                </li>

                <li class="menu-title">System</li>

                <li>
                    <a href="{{ route('reports.index') }}">
                        <i class="bx bx-bar-chart-alt-2 icon nav-icon"></i>
                        <span class="menu-item">Reports</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="bx bx-cog icon nav-icon"></i>
                        <span class="menu-item">Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('settings.profile') }}">School Profile</a></li>
                        <li><a href="{{ route('settings.brand') }}">Brand Settings</a></li>
                        <li><a href="{{ route('settings.users') }}">Users & Roles</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    /* Submenus start collapsed; only .mm-show reveals them */
    #side-menu .sub-menu {
        display: none;
    }
    #side-menu .sub-menu.mm-show {
        display: block;
    }
    /* Rotate the has-arrow indicator when its menu is open */
    #side-menu li.mm-active > a.has-arrow:after {
        transform: rotate(90deg);
    }
</style>

<script>
(function () {
    function initSidebarToggle() {
        var topLevelLinks = document.querySelectorAll('#side-menu > li > a.has-arrow');

        topLevelLinks.forEach(function (link) {
            var submenu = link.nextElementSibling;
            if (!submenu || !submenu.classList.contains('sub-menu')) return;

            // Ensure collapsed on load regardless of any markup/JS that ran before this
            submenu.classList.remove('mm-show');
            submenu.setAttribute('aria-expanded', 'false');
            link.parentElement.classList.remove('mm-active');

            link.addEventListener('click', function (e) {
                e.preventDefault();
                var parentLi = link.parentElement;
                var isOpen = submenu.classList.contains('mm-show');

                // Accordion behavior: close any other open submenu first
                document.querySelectorAll('#side-menu > li > ul.sub-menu.mm-show').forEach(function (openMenu) {
                    if (openMenu !== submenu) {
                        openMenu.classList.remove('mm-show');
                        openMenu.setAttribute('aria-expanded', 'false');
                        openMenu.parentElement.classList.remove('mm-active');
                    }
                });

                if (isOpen) {
                    submenu.classList.remove('mm-show');
                    submenu.setAttribute('aria-expanded', 'false');
                    parentLi.classList.remove('mm-active');
                } else {
                    submenu.classList.add('mm-show');
                    submenu.setAttribute('aria-expanded', 'true');
                    parentLi.classList.add('mm-active');
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarToggle);
    } else {
        initSidebarToggle();
    }
})();
</script>

