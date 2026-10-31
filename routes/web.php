<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\OfficeSettingController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Absensi: semua role yang sudah login
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    // Cuti: semua role yang sudah login
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');

    // Persetujuan: manager dan admin
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/leave-approvals', [LeaveRequestController::class, 'approvals'])->name('leave-approvals.index');
        Route::patch('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::patch('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    }); 

    // Evaluasi kinerja: semua role bisa melihat, hanya manager/admin yang mengelola
    Route::get('/performance-reviews', [PerformanceReviewController::class, 'index'])->name('performance-reviews.index');
    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('performance-reviews', PerformanceReviewController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // Payroll: admin melihat semua dan generate, yang lain hanya slip sendiri (dijaga Policy)
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::post('/payrolls/generate', [PayrollController::class, 'generate'])
        ->middleware('role:admin')->name('payrolls.generate');
    Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');

    // Hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('departments', DepartmentController::class)->except('show');
        Route::resource('employees', EmployeeController::class)->except('show');

        Route::get('/office-settings', [OfficeSettingController::class, 'edit'])->name('office-settings.edit');
        Route::put('/office-settings', [OfficeSettingController::class, 'update'])->name('office-settings.update');
    });
});