<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\PlannedTrainingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TrainingCategoryController;
use App\Http\Controllers\TrainingInstitutionController;
use App\Http\Controllers\TrainingOpportunityController;
use App\Http\Controllers\UnplannedTrainingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

    Route::resource('planned-trainings',
        PlannedTrainingController::class);

    Route::resource('unplanned-trainings',
        UnplannedTrainingController::class);

    Route::resource('training-opportunities',
        TrainingOpportunityController::class);

    Route::resource('training-categories',
        TrainingCategoryController::class);

    Route::resource('training-institutions',
        TrainingInstitutionController::class);

});

require __DIR__.'/auth.php';
