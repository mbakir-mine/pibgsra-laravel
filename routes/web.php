<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\FamilyFeeChargeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MonthClosingController;
use App\Http\Controllers\StatementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/families', [FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/create', [FamilyController::class, 'create'])->name('families.create');
    Route::post('/families', [FamilyController::class, 'store'])->name('families.store');
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/fees', [FeeCategoryController::class, 'index'])->name('fees.index');
    Route::get('/fee-settings', [FeeCategoryController::class, 'index'])->name('fee-settings');
    Route::get('/fees/create', [FeeCategoryController::class, 'create'])->name('fees.create');
    Route::post('/fees', [FeeCategoryController::class, 'store'])->name('fees.store');
    Route::get('/charges', [FamilyFeeChargeController::class, 'index'])->name('charges.index');
    Route::get('/charges/create', [FamilyFeeChargeController::class, 'create'])->name('charges.create');
    Route::post('/charges', [FamilyFeeChargeController::class, 'store'])->name('charges.store');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/uat-checkout', [PaymentController::class, 'uatCheckout'])->name('payments.uat-checkout');
    Route::post('/payments/{payment}/uat-success', [PaymentController::class, 'uatSuccess'])->name('payments.uat-success');
    Route::post('/payments/{payment}/cancel-receipt', [PaymentController::class, 'cancelReceipt'])->name('payments.cancel-receipt');
    Route::post('/payments/{payment}/request-cancellation', [PaymentController::class, 'requestCancellation'])->name('payments.request-cancellation');
    Route::post('/payments/{payment}/approve-cancellation', [PaymentController::class, 'approveCancellation'])->name('payments.approve-cancellation');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/month-closing', [MonthClosingController::class, 'index'])->name('month-closing.index');
    Route::post('/month-closing', [MonthClosingController::class, 'store'])->name('month-closing.store');
    Route::get('/families/{family}/statement', [StatementController::class, 'show'])->name('statements.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
