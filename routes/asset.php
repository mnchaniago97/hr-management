<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asset\AssetCategoryController;
use App\Http\Controllers\Asset\AssetItemController;
use App\Http\Controllers\Asset\AssetAssignmentController;
use App\Http\Controllers\Asset\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Asset Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Asset module routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::prefix('asset')->name('asset.')->group(function () {
    // Categories - /asset/categories
    Route::resource('categories', AssetCategoryController::class)->parameters([
        'categories' => 'id'
    ]);

    // Items - /asset/items
    Route::resource('items', AssetItemController::class)->parameters([
        'items' => 'id'
    ]);

    // Items - Additional Routes
    Route::post('/items/import', [AssetItemController::class, 'import'])->name('items.import');
    Route::get('/items/export', [AssetItemController::class, 'export'])->name('items.export');
    Route::get('/items/available', [AssetItemController::class, 'getAvailable'])->name('items.available');
    Route::get('/items/qrcode', function () {
        return view('asset.items.qrcode');
    })->name('items.qrcode');

    // Loans / Assignments - /asset/loans and /asset/assignments
    // Using assignments as primary (loan functionality is part of assignment)
    Route::resource('assignments', AssetAssignmentController::class)->parameters([
        'assignments' => 'id'
    ]);
    
    // Additional Assignment Routes
    Route::post('/assignments/{id}/return', [AssetAssignmentController::class, 'return'])->name('assignments.return');
    Route::post('/assignments/{id}/extend', [AssetAssignmentController::class, 'extend'])->name('assignments.extend');
    Route::get('/assignments/overdue', [AssetAssignmentController::class, 'getOverdue'])->name('assignments.overdue');

    // Also support /loans as alternative route (alias to assignments)
    Route::get('/loans', [AssetAssignmentController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [AssetAssignmentController::class, 'create'])->name('loans.create');
    Route::get('/loans/return', function () {
        return view('asset.loans.return');
    })->name('loans.return.form');
    Route::post('/loans', [AssetAssignmentController::class, 'store'])->name('loans.store');
    Route::get('/loans/{id}', [AssetAssignmentController::class, 'show'])->name('loans.show');
    Route::post('/loans/{id}/return', [AssetAssignmentController::class, 'return'])->name('loans.return');
    Route::post('/loans/{id}/extend', [AssetAssignmentController::class, 'extend'])->name('loans.extend');
    Route::delete('/loans/{id}', [AssetAssignmentController::class, 'destroy'])->name('loans.destroy');
    Route::get('/loans/overdue', [AssetAssignmentController::class, 'getOverdue'])->name('loans.overdue');

    // Maintenance - /asset/maintenance
    Route::resource('maintenance', MaintenanceController::class)->parameters([
        'maintenance' => 'id'
    ]);
    
    // Additional Maintenance Routes
    Route::get('/maintenance/upcoming', [MaintenanceController::class, 'upcoming'])->name('maintenance.upcoming');

    // History
    Route::get('/history', function () {
        return view('asset.history.index');
    })->name('history.index');
});
