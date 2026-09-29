<aside class="app-sidebar" id="appSidebar">
    <a href="{{ route('tenant.dashboard') }}" class="sidebar-brand">
        <span class="brand-icon"><i class="bi bi-mortarboard"></i></span>
        <span class="brand-text">{{ app('tenant')->name }}</span>
    </a>
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <li class="sidebar-heading">{{ __('app.main') }}</li>
            <li class="menu-item">
                <a class="menu-link" href="{{ route('tenant.dashboard') }}">
                    <i class="bi bi-house-door"></i><span class="menu-text">{{ __('app.dashboard') }}</span>
                </a>
            </li>

            @if(auth()->user()->hasAnyRole(['Teacher', 'School Admin']))
            <li class="menu-item">
                <a class="menu-link" href="{{ route('my-schedule.index') }}">
                    <i class="bi bi-calendar-week"></i><span class="menu-text">{{ __('app.my_schedule') }}</span>
                </a>
            </li>
            @endif

            <li class="sidebar-heading">{{ __('app.school_section') }}</li>

            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#studentsCollapse" href="#" role="button">
                    <i class="bi bi-people"></i><span class="menu-text">{{ __('app.students') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="studentsCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('students.index') }}">{{ __('app.all_students') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('students.create') }}">{{ __('app.add_student') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('guardians.index') }}">{{ __('app.parent_accounts') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('students.import') }}">{{ __('app.bulk_import') }}</a></li>
                    </ul>
                </div>
            </li>

            @if (app('tenant')->hasModule('academics'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#academicsCollapse" href="#" role="button">
                    <i class="bi bi-journal-bookmark"></i><span class="menu-text">{{ __('app.academics') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="academicsCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('classes.index') }}">{{ __('app.classes') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('sections.index') }}">{{ __('app.sections') }}</a></li>
                        @if (app('tenant')->hasModule('timetable'))
                            <li><a class="submenu-link" href="{{ route('timetable.index') }}">{{ __('app.timetable') }}</a></li>
                        @endif
                        <li><a class="submenu-link" href="{{ route('promotion.index') }}">{{ __('app.promotion') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('attendance'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#attendanceCollapse" href="#" role="button">
                    <i class="bi bi-calendar-check"></i><span class="menu-text">{{ __('app.attendance') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="attendanceCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('attendance.create') }}">{{ __('app.mark_attendance') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('attendance.report') }}">{{ __('app.attendance_report') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('examinations'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#examCollapse" href="#" role="button">
                    <i class="bi bi-file-earmark-text"></i><span class="menu-text">{{ __('app.examinations') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="examCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('subjects.index') }}">{{ __('app.subjects') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('examinations.index') }}">{{ __('app.exams') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('fees'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#feesCollapse" href="#" role="button">
                    <i class="bi bi-cash-coin"></i><span class="menu-text">{{ __('app.fees_finance') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="feesCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('fees.categories') }}">{{ __('app.fee_categories') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('fees.structures') }}">{{ __('app.fee_structure') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('fees.settings') }}">{{ __('app.school_bank_settings') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('fees.scholarships') }}">{{ __('app.scholarships') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('fees.invoices') }}">{{ __('app.invoices') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('finance.income') }}">{{ __('app.income') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('finance.expenses') }}">{{ __('app.expenses') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('hr'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#hrCollapse" href="#" role="button">
                    <i class="bi bi-briefcase"></i><span class="menu-text">{{ __('app.hr_staff') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="hrCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('hr.employees') }}">{{ __('app.employees') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('hr.leaves') }}">{{ __('app.leave_requests') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('hr.payroll') }}">{{ __('app.payroll') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('library'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#libraryCollapse" href="#" role="button">
                    <i class="bi bi-book"></i><span class="menu-text">{{ __('app.library') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="libraryCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('library.books') }}">{{ __('app.books') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('library.issues') }}">{{ __('app.issue_return') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if (app('tenant')->hasModule('transport'))
            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#transportCollapse" href="#" role="button">
                    <i class="bi bi-bus-front"></i><span class="menu-text">{{ __('app.transport') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="transportCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('transport.vehicles') }}">{{ __('app.vehicles') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('transport.routes') }}">{{ __('app.routes') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('transport.assignments') }}">{{ __('app.student_assignments') }}</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <li class="sidebar-heading">{{ __('app.system') }}</li>

            @if (app('tenant')->hasModule('reports'))
            <li class="menu-item">
                <a class="menu-link" href="{{ route('reports.index') }}">
                    <i class="bi bi-bar-chart"></i><span class="menu-text">{{ __('app.reports') }}</span>
                </a>
            </li>
            @endif

            <li class="menu-item">
                <a class="menu-link" data-bs-toggle="collapse" data-bs-target="#settingsCollapse" href="#" role="button">
                    <i class="bi bi-gear"></i><span class="menu-text">{{ __('app.settings') }}</span>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                <div id="settingsCollapse" class="collapse">
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-link" href="{{ route('settings.profile') }}">{{ __('app.school_profile') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('settings.brand') }}">{{ __('app.brand_settings') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('settings.users') }}">{{ __('app.users_roles') }}</a></li>
                        <li><a class="submenu-link" href="{{ route('activity-log.index') }}">{{ __('app.activity_log') }}</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</aside>