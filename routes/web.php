<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\PlannedTrainingController;
use App\Http\Controllers\UnplannedTrainingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FundingSourceController;
use App\Http\Controllers\TrainingCategoryController;
use App\Http\Controllers\TrainingInstitutionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-only CRUD resources
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);

        Route::resource('financial-years',
            FinancialYearController::class);

        Route::resource('departments',
            DepartmentController::class);

        Route::resource('training-categories',
            TrainingCategoryController::class);

        Route::resource('training-institutions',
            TrainingInstitutionController::class);

        Route::resource('funding-sources',
            FundingSourceController::class);

        Route::get('audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])
            ->name('audit-logs.index');
    });

    Route::resource('staff',
        StaffController::class);

    Route::prefix('planned-trainings')->name('planned-trainings.')->group(function () {
        Route::get('import', [PlannedTrainingController::class, 'importForm'])->name('import');
        Route::post('import', [PlannedTrainingController::class, 'importStore'])->name('import.store');
        Route::get('import/template', [PlannedTrainingController::class, 'downloadTemplate'])->name('import.template');
    });

    Route::resource('planned-trainings',
        PlannedTrainingController::class);

    Route::prefix('unplanned-trainings')->name('unplanned-trainings.')->group(function () {
        Route::get('import', [UnplannedTrainingController::class, 'importForm'])->name('import');
        Route::post('import', [UnplannedTrainingController::class, 'importStore'])->name('import.store');
        Route::get('import/template', [UnplannedTrainingController::class, 'downloadTemplate'])->name('import.template');
    });

    Route::resource('unplanned-trainings',
        UnplannedTrainingController::class);

    // TNA Management
    Route::prefix('tna/exercises')->name('tna.exercises.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TnaExerciseController::class, 'index'])->name('index');
        Route::get('create', [\App\Http\Controllers\TnaExerciseController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\TnaExerciseController::class, 'store'])->name('store');
        Route::get('{exercise}', [\App\Http\Controllers\TnaExerciseController::class, 'show'])->name('show');
        Route::get('{exercise}/edit', [\App\Http\Controllers\TnaExerciseController::class, 'edit'])->name('edit');
        Route::put('{exercise}', [\App\Http\Controllers\TnaExerciseController::class, 'update'])->name('update');
        Route::delete('{exercise}', [\App\Http\Controllers\TnaExerciseController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('tna/import')->name('tna.import.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TnaImportController::class, 'form'])->name('form');
        Route::post('/', [\App\Http\Controllers\TnaImportController::class, 'store'])->name('store');
        Route::get('template', [\App\Http\Controllers\TnaImportController::class, 'downloadTemplate'])->name('template');
    });

    Route::prefix('tna/responses')->name('tna.responses.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TnaResponseController::class, 'index'])->name('index');
        Route::get('{response}', [\App\Http\Controllers\TnaResponseController::class, 'show'])->name('show');
        Route::get('{response}/edit', [\App\Http\Controllers\TnaResponseController::class, 'edit'])->name('edit');
        Route::put('{response}', [\App\Http\Controllers\TnaResponseController::class, 'update'])->name('update');
        Route::delete('{response}', [\App\Http\Controllers\TnaResponseController::class, 'destroy'])->name('destroy');
        Route::get('export', [\App\Http\Controllers\TnaResponseController::class, 'export'])->name('export');
    });

    Route::prefix('tna/analysis')->name('tna.analysis.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TnaAnalysisController::class, 'index'])->name('index');
    });

    Route::prefix('tna/plan')->name('tna.plan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TnaPlanController::class, 'index'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\TnaPlanController::class, 'generate'])->name('generate');
        Route::delete('/{training}', [\App\Http\Controllers\TnaPlanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('training-summary', [\App\Http\Controllers\ReportController::class, 'trainingSummary'])->name('training-summary');
        Route::get('department', [\App\Http\Controllers\ReportController::class, 'departmentReport'])->name('department');
        Route::get('staff', [\App\Http\Controllers\ReportController::class, 'staffReport'])->name('staff');
        Route::get('staff/{staff}', [\App\Http\Controllers\ReportController::class, 'staffShow'])->name('staff.show');
        Route::get('financial', [\App\Http\Controllers\ReportController::class, 'financialReport'])->name('financial');
        Route::get('cost', [\App\Http\Controllers\ReportController::class, 'costReport'])->name('cost');
        Route::get('status', [\App\Http\Controllers\ReportController::class, 'statusReport'])->name('status');
        Route::get('duration', [\App\Http\Controllers\ReportController::class, 'durationReport'])->name('duration');
        Route::get('export/{type}', [\App\Http\Controllers\ReportController::class, 'export'])->name('export');
    });

    Route::get('user-manual', [\App\Http\Controllers\ManualController::class, 'userManual'])->name('user-manual');

});

require __DIR__.'/auth.php';
