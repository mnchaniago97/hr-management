<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\Hr\AttendanceController;
use App\Http\Controllers\Asset\AssetAssignmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Hr\MemberSelfServiceController;

// Dashboard (auth only)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');
});

// Public attendance
Route::get('/attendance', [AttendanceController::class, 'publicForm'])->name('attendance.public');
Route::post('/attendance/check-in', [AttendanceController::class, 'quickCheckIn'])->name('attendance.public.checkin');
Route::post('/attendance/check-out', [AttendanceController::class, 'quickCheckOut'])->name('attendance.public.checkout');

// Public asset loan
Route::get('/asset-loan', [AssetAssignmentController::class, 'publicForm'])->name('asset.public.loan');
Route::post('/asset-loan', [AssetAssignmentController::class, 'publicStore'])->name('asset.public.loan.store');

// Include route files for modular routes (auth only)
Route::middleware('auth')->group(function () {
    require __DIR__.'/hr.php';
    require __DIR__.'/asset.php';
    require __DIR__.'/program.php';
});

// Calendar
Route::middleware('auth')->group(function () {
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/account-settings', [ProfileController::class, 'show'])->name('account-settings');
    Route::get('/anggota/mandiri', [MemberSelfServiceController::class, 'index'])->name('member.self-service');
    Route::post('/anggota/mandiri/{memberId}', [MemberSelfServiceController::class, 'update'])->name('member.self-service.update');
});

    // Form pages
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // Tables pages
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // Blank page
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // Error pages
    Route::get('/error-404', function () {
        return view('pages.errors.error-404', ['title' => 'Error 404']);
    })->name('error-404');

    // Chart pages
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

// Authentication pages
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin - User Management (Super Admin only)
Route::prefix('admin')->name('admin.')->middleware('role:Super Admin')->group(function () {
    Route::resource('users', UserController::class)->parameters([
        'users' => 'id'
    ])->whereNumber('id');
});

Route::get('/register', function () {
    return view('pages.auth.signup', ['title' => 'Register']);
})->name('register');

    // UI elements pages
    Route::get('/alerts', function () {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    })->name('alerts');

    Route::get('/avatars', function () {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    })->name('avatars');

    Route::get('/badge', function () {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    })->name('badges');

    Route::get('/buttons', function () {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    })->name('buttons');

    Route::get('/image', function () {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    })->name('images');

    Route::get('/videos', function () {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    })->name('videos');
});
