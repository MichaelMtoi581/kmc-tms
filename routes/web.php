<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\PlannedTrainingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FundingSourceController;
use App\Http\Controllers\TrainingCategoryController;
use App\Http\Controllers\TrainingInstitutionController;
use App\Http\Controllers\TrainingOpportunityController;
use App\Http\Controllers\UnplannedTrainingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('financial-years',
        FinancialYearController::class);

    Route::resource('departments',
        DepartmentController::class);

    Route::resource('staff',
        StaffController::class);

    Route::prefix('planned-trainings')->name('planned-trainings.')->group(function () {
        Route::get('import', [PlannedTrainingController::class, 'importForm'])->name('import');
        Route::post('import', [PlannedTrainingController::class, 'importStore'])->name('import.store');
    });

    Route::resource('planned-trainings',
        PlannedTrainingController::class);

    Route::prefix('unplanned-trainings')->name('unplanned-trainings.')->group(function () {
        Route::get('import', [UnplannedTrainingController::class, 'importForm'])->name('import');
        Route::post('import', [UnplannedTrainingController::class, 'importStore'])->name('import.store');
    });

    Route::resource('unplanned-trainings',
        UnplannedTrainingController::class);

    Route::resource('training-opportunities',
        TrainingOpportunityController::class);

    Route::resource('training-categories',
        TrainingCategoryController::class);

    Route::resource('training-institutions',
        TrainingInstitutionController::class);

    Route::resource('funding-sources',
        FundingSourceController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('training-summary', [\App\Http\Controllers\ReportController::class, 'trainingSummary'])->name('training-summary');
        Route::get('department', [\App\Http\Controllers\ReportController::class, 'departmentReport'])->name('department');
        Route::get('staff', [\App\Http\Controllers\ReportController::class, 'staffReport'])->name('staff');
        Route::get('financial', [\App\Http\Controllers\ReportController::class, 'financialReport'])->name('financial');
        Route::get('cost', [\App\Http\Controllers\ReportController::class, 'costReport'])->name('cost');
        Route::get('status', [\App\Http\Controllers\ReportController::class, 'statusReport'])->name('status');
    });

});

require __DIR__.'/auth.php';
