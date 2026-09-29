<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\StudentController;
use App\Http\Controllers\Tenant\ClassController;
use App\Http\Controllers\Tenant\SectionController;
use App\Http\Controllers\Tenant\AttendanceController;
use App\Http\Controllers\Tenant\ExaminationController;
use App\Http\Controllers\Tenant\FeeController;
use App\Http\Controllers\Tenant\HrController;
use App\Http\Controllers\Tenant\LibraryController;
use App\Http\Controllers\Tenant\TransportController;
use App\Http\Controllers\Tenant\FinanceController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\TimetableController;
use App\Http\Controllers\Tenant\Auth\PasswordResetController;
use App\Http\Controllers\Tenant\Portal\PortalAuthController;
use App\Http\Controllers\Tenant\Portal\PortalController;
use App\Http\Controllers\Tenant\GuardianController;
use App\Http\Controllers\Tenant\PromotionController;
use App\Http\Controllers\Tenant\ActivityLogController;
use App\Http\Controllers\Tenant\TeacherScheduleController;
use App\Http\Controllers\Tenant\SearchController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', \App\Http\Middleware\IdentifyTenant::class])->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('login.store');
        Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');

        // Students — no module gate; core identity data other modules depend on
        Route::resource('students', StudentController::class)->except(['show'])->parameters(['students' => 'student']);
        Route::get('/students/export/csv', [StudentController::class, 'export'])->name('students.export');
        Route::get('/students/import/template', [StudentController::class, 'importTemplate'])->name('students.import.template');
        Route::get('/students/import', [StudentController::class, 'showImport'])->name('students.import');
        Route::post('/students/import', [StudentController::class, 'processImport'])->name('students.import.process');

        Route::middleware('module:academics')->group(function () {
            Route::resource('classes', ClassController::class)->except(['show'])->parameters(['classes' => 'school_class']);
            Route::resource('sections', SectionController::class)->except(['show'])->parameters(['sections' => 'section']);
            Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion.index');
            Route::post('/promotion', [PromotionController::class, 'promote'])->name('promotion.promote');
            Route::get('/promotion/sections-for', [PromotionController::class, 'sectionsFor'])->name('promotion.sections-for');
            Route::get('/my-schedule', [TeacherScheduleController::class, 'index'])->name('my-schedule.index');
        });

        Route::middleware('module:attendance')->group(function () {
            Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
            Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
            Route::get('/attendance/report/export/csv', [AttendanceController::class, 'exportReport'])->name('attendance.report.export');
        });

        Route::middleware('module:examinations')->group(function () {
            Route::get('/examinations/subjects', [ExaminationController::class, 'subjects'])->name('subjects.index');
            Route::post('/examinations/subjects', [ExaminationController::class, 'storeSubject'])->name('subjects.store');

            Route::get('/examinations', [ExaminationController::class, 'index'])->name('examinations.index');
            Route::post('/examinations', [ExaminationController::class, 'store'])->name('examinations.store');
            Route::get('/examinations/{exam}', [ExaminationController::class, 'show'])->name('examinations.show');
            Route::post('/examinations/{exam}/subjects', [ExaminationController::class, 'attachSubject'])->name('examinations.subjects.attach');

            Route::get('/exams/{examSubject}/marks', [ExaminationController::class, 'markEntry'])->name('exams.marks');
            Route::post('/exams/{examSubject}/marks', [ExaminationController::class, 'saveMarks'])->name('exams.marks.save');

            Route::get('/examinations/{exam}/students/{student}/report-card', [ExaminationController::class, 'reportCard'])->name('examinations.report-card');
        });

        Route::middleware('module:fees')->group(function () {
            Route::get('/fees/categories', [FeeController::class, 'categories'])->name('fees.categories');
            Route::post('/fees/categories', [FeeController::class, 'storeCategory'])->name('fees.categories.store');

            Route::get('/fees/structures', [FeeController::class, 'structures'])->name('fees.structures');
            Route::post('/fees/structures/generate', [FeeController::class, 'generateClassFees'])->name('fees.structures.generate');
            Route::get('/fees/print-batch', [FeeController::class, 'printBatch'])->name('fees.print-batch');
            Route::get('/fees/settings', [FeeController::class, 'settings'])->name('fees.settings');
            Route::post('/fees/settings', [FeeController::class, 'updateSettings'])->name('fees.settings.update');

            Route::get('/fees/scholarships', [FeeController::class, 'scholarships'])->name('fees.scholarships');
            Route::post('/fees/scholarships', [FeeController::class, 'storeScholarship'])->name('fees.scholarships.store');

            Route::get('/fees/invoices', [FeeController::class, 'invoices'])->name('fees.invoices');
            Route::post('/fees/invoices', [FeeController::class, 'storeInvoice'])->name('fees.invoices.store');
            Route::get('/fees/invoices/{invoice}', [FeeController::class, 'showInvoice'])->name('fees.invoices.show');
            Route::post('/fees/invoices/{invoice}/payments', [FeeController::class, 'recordPayment'])->name('fees.invoices.payments.store');
            Route::post('/fees/invoices/{invoice}/fine', [FeeController::class, 'applyFine'])->name('fees.invoices.fine');

            Route::get('/finance/income', [FinanceController::class, 'income'])->name('finance.income');
            Route::post('/finance/income', [FinanceController::class, 'storeIncome'])->name('finance.income.store');

            Route::get('/finance/expenses', [FinanceController::class, 'expenses'])->name('finance.expenses');
            Route::post('/finance/expenses', [FinanceController::class, 'storeExpense'])->name('finance.expenses.store');
        });

        Route::middleware('module:hr')->group(function () {
            Route::get('/hr/employees', [HrController::class, 'employees'])->name('hr.employees');
            Route::get('/hr/employees/create', [HrController::class, 'createEmployee'])->name('hr.employees.create');
            Route::post('/hr/employees', [HrController::class, 'storeEmployee'])->name('hr.employees.store');
            Route::get('/hr/employees/{employee}/edit', [HrController::class, 'editEmployee'])->name('hr.employees.edit');
            Route::put('/hr/employees/{employee}', [HrController::class, 'updateEmployee'])->name('hr.employees.update');

            Route::get('/hr/leaves', [HrController::class, 'leaves'])->name('hr.leaves');
            Route::post('/hr/leaves', [HrController::class, 'storeLeave'])->name('hr.leaves.store');
            Route::post('/hr/leaves/{leave}/review', [HrController::class, 'reviewLeave'])->name('hr.leaves.review');

            Route::get('/hr/payroll', [HrController::class, 'payroll'])->name('hr.payroll');
            Route::post('/hr/payroll', [HrController::class, 'generatePayroll'])->name('hr.payroll.generate');
            Route::post('/hr/payroll/{payroll}/mark-paid', [HrController::class, 'markPaid'])->name('hr.payroll.mark-paid');
        });

        Route::middleware('module:library')->group(function () {
            Route::get('/library/books', [LibraryController::class, 'books'])->name('library.books');
            Route::post('/library/books', [LibraryController::class, 'storeBook'])->name('library.books.store');
            Route::get('/library/books/{book}/edit', [LibraryController::class, 'editBook'])->name('library.books.edit');
            Route::put('/library/books/{book}', [LibraryController::class, 'updateBook'])->name('library.books.update');

            Route::get('/library/issues', [LibraryController::class, 'issues'])->name('library.issues');
            Route::post('/library/issues', [LibraryController::class, 'storeIssue'])->name('library.issues.store');
            Route::post('/library/issues/{issue}/return', [LibraryController::class, 'returnBook'])->name('library.issues.return');
        });

        Route::middleware('module:transport')->group(function () {
            Route::get('/transport/vehicles', [TransportController::class, 'vehicles'])->name('transport.vehicles');
            Route::post('/transport/vehicles', [TransportController::class, 'storeVehicle'])->name('transport.vehicles.store');

            Route::get('/transport/routes', [TransportController::class, 'routes'])->name('transport.routes');
            Route::post('/transport/routes', [TransportController::class, 'storeRoute'])->name('transport.routes.store');
            Route::get('/transport/routes/{route}', [TransportController::class, 'showRoute'])->name('transport.routes.show');
            Route::post('/transport/routes/{route}/stops', [TransportController::class, 'storeStop'])->name('transport.routes.stops.store');

            Route::get('/transport/assignments', [TransportController::class, 'assignments'])->name('transport.assignments');
            Route::post('/transport/assignments', [TransportController::class, 'assignStudent'])->name('transport.assignments.store');
        });

        Route::middleware('module:timetable')->group(function () {
            Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
            Route::post('/timetable', [TimetableController::class, 'store'])->name('timetable.store');
            Route::delete('/timetable/{slot}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
        });

        Route::middleware('module:reports')->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        });

        // Settings & profile — always accessible, never module-gated
        Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');

        Route::get('/settings/users', [SettingsController::class, 'users'])->name('settings.users');
        Route::post('/settings/users', [SettingsController::class, 'storeUser'])->name('settings.users.store');
        Route::post('/settings/users/{user}/role', [SettingsController::class, 'updateUserRole'])->name('settings.users.role');
        Route::post('/settings/users/{user}/toggle-status', [SettingsController::class, 'toggleUserStatus'])->name('settings.users.toggle-status');

        Route::get('/settings/brand', [SettingsController::class, 'brand'])->name('settings.brand');
        Route::post('/settings/brand', [SettingsController::class, 'updateBrand'])->name('settings.brand.update');

        Route::get('/my-profile', [SettingsController::class, 'myProfile'])->name('my-profile');
        Route::post('/my-profile', [SettingsController::class, 'updateMyProfile'])->name('my-profile.update');

        Route::get('/guardians', [GuardianController::class, 'index'])->name('guardians.index');
        Route::post('/guardians', [GuardianController::class, 'store'])->name('guardians.store');
        Route::post('/guardians/{guardian}/students', [GuardianController::class, 'updateStudents'])->name('guardians.students.update');
        
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

        Route::get('/search', [SearchController::class, 'index'])->name('search.index');
        

        Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
    });

});

Route::middleware('guest:guardian')->group(function () {
    Route::get('/portal/login', [PortalAuthController::class, 'create'])->name('portal.login');
    Route::post('/portal/login', [PortalAuthController::class, 'store'])->name('portal.login.store');
});

Route::middleware('auth:guardian')->group(function () {
    Route::post('/portal/logout', [PortalAuthController::class, 'destroy'])->name('portal.logout');
    Route::get('/portal', [PortalController::class, 'dashboard'])->name('portal.dashboard');
    Route::get('/portal/students/{student}/attendance', [PortalController::class, 'attendance'])->name('portal.attendance');
    Route::get('/portal/students/{student}/fees', [PortalController::class, 'fees'])->name('portal.fees');
    Route::get('/portal/students/{student}/results', [PortalController::class, 'results'])->name('portal.results');
    Route::get('/portal/students/{student}/timetable', [PortalController::class, 'timetable'])->name('portal.timetable');
    Route::get('/portal/students/{student}/exams/{exam}/report-card', [PortalController::class, 'reportCard'])->name('portal.report-card');
});