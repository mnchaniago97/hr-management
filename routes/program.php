<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Program\PeriodController;
use App\Http\Controllers\Program\FieldController;
use App\Http\Controllers\Program\DivisionController;
use App\Http\Controllers\Program\ProgramController;
use App\Http\Controllers\Program\ActivityController;
use App\Http\Controllers\Program\DocumentController;
use App\Http\Controllers\Program\TrainingController;
use App\Http\Controllers\Program\TrainingParticipantController;

/*
|--------------------------------------------------------------------------
| Program Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Program module routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::prefix('program')->name('program.')->group(function () {
    // Periods - /program/periods
    Route::resource('periods', PeriodController::class)->parameters([
        'periods' => 'id'
    ]);

    // Fields (Bidang) - /program/fields
    Route::resource('fields', FieldController::class)->parameters([
        'fields' => 'id'
    ]);

    // Divisions - /program/divisions
    Route::resource('divisions', DivisionController::class)->parameters([
        'divisions' => 'id'
    ]);

    // Programs - /program/programs
    Route::resource('programs', ProgramController::class)->parameters([
        'programs' => 'id'
    ]);

    // Program filters
    Route::get('/programs/period/{periodId}', [ProgramController::class, 'getByPeriod'])->name('programs.by-period');
    Route::get('/programs/field/{fieldId}', [ProgramController::class, 'getByField'])->name('programs.by-field');
    Route::get('/programs/division/{divisionId}', [ProgramController::class, 'getByDivision'])->name('programs.by-division');

    // Activities - /program/activities
    Route::resource('activities', ActivityController::class)->parameters([
        'activities' => 'id'
    ]);
    
    // Additional Activity Routes
    Route::get('/activities/calendar', [ActivityController::class, 'calendar'])->name('activities.calendar');
    Route::post('/activities/{activity}/status', [ActivityController::class, 'updateStatus'])->name('activities.update-status');

    // Documents - /program/documents
    Route::resource('documents', DocumentController::class)->parameters([
        'documents' => 'id'
    ]);
    
    // Additional Document Routes
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Training - /program/training
    Route::resource('training', TrainingController::class)->parameters([
        'training' => 'id'
    ]);

    // Training participants
    Route::prefix('participants')->name('participants.')->group(function () {
        Route::get('/', [TrainingParticipantController::class, 'index'])->name('index');
        Route::get('/{id}', [TrainingParticipantController::class, 'show'])->name('show');
        Route::get('/{id}/certificate', [TrainingParticipantController::class, 'certificate'])->name('certificate');
        Route::get('/training/{trainingId}', [TrainingParticipantController::class, 'getByTraining'])->name('by-training');
        Route::get('/report/attendance', [TrainingParticipantController::class, 'attendanceReport'])->name('attendance-report');
    });

    // Reports (view-only stubs)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', fn () => view('program.reports.index'))->name('index');
        Route::get('/by-period', fn () => view('program.reports.by-period'))->name('by-period');
        Route::get('/by-field', fn () => view('program.reports.by-field'))->name('by-field');
        Route::get('/by-division', fn () => view('program.reports.by-division'))->name('by-division');
        Route::get('/monthly', fn () => view('program.reports.monthly'))->name('monthly');
        Route::get('/export', fn () => view('program.reports.export'))->name('export');
    });

    // Statuses (view-only stubs)
    Route::prefix('statuses')->name('statuses.')->group(function () {
        Route::get('/', fn () => view('program.statuses.index'))->name('index');
        Route::get('/create', fn () => view('program.statuses.create'))->name('create');
        Route::get('/{id}/edit', fn () => view('program.statuses.edit'))->name('edit');
    });
});
