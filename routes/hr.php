<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\MemberController;
use App\Http\Controllers\Hr\PositionController;
use App\Http\Controllers\Hr\DivisionAssignmentController;
use App\Http\Controllers\Hr\AttendanceController;
use App\Http\Controllers\Hr\LeaveRequestController;
use App\Http\Controllers\Hr\RecruitmentController;
use App\Http\Controllers\Hr\TrainingController;
use App\Http\Controllers\Hr\CertificationController;

/*
|--------------------------------------------------------------------------
| HR Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register HR module routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::prefix('hr')->name('hr.')->group(function () {
    // Member utilities
    Route::get('/members/import', fn () => view('hr.members.import'))->name('members.import');
    Route::post('/members/import', [MemberController::class, 'import'])->name('members.import.store');
    Route::get('/members/cards', fn () => view('hr.members.cards'))->name('members.cards');

    // Members CRUD - /hr/members
    Route::resource('members', MemberController::class)->parameters([
        'members' => 'id'
    ])->whereNumber('id');

    // Positions - /hr/positions
    Route::resource('positions', PositionController::class)->parameters([
        'positions' => 'id'
    ]);

    // Division Assignments - /hr/assignments
    Route::resource('assignments', DivisionAssignmentController::class)->parameters([
        'assignments' => 'id'
    ]);

    // Attendance - /hr/attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    
    // Also support /attendances as alternative
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/{id}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');

    // Leave Requests - /hr/leave-requests
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{id}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::delete('/leave-requests/{id}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');

    // Recruitment - /hr/recruitment
    Route::resource('recruitment', RecruitmentController::class)->parameters([
        'recruitment' => 'id'
    ]);

    // Trainings - /hr/trainings
    Route::resource('trainings', TrainingController::class)->parameters([
        'trainings' => 'id'
    ]);

    // Certifications - /hr/certifications
    Route::resource('certifications', CertificationController::class)->parameters([
        'certifications' => 'id'
    ]);

    // Training participants
    Route::get('/training-participants', fn () => view('hr.training-participants.index'))->name('training-participants.index');

});
