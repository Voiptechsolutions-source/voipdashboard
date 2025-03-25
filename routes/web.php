<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\LeadController;
use App\Http\Controllers\Customer\CustomerImportController;
use App\Http\Controllers\Customer\ConvertedLeadsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Admin\DeleteController;



// Show login form at the root URL
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Handle the login form submission
Route::post('/login', [AuthController::class, 'login'])->name('logins');

// Dashboard route, protected by auth middleware
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Handle logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Customer routes
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

Route::post('/update-status', [CustomerController::class, 'updateStatus'])->name('customers.updateStatus');

Route::middleware(['auth:web'])->group(function () {
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
});

Route::get('/customers/{id}/edit', [CustomerController::class, 'edit']);

Route::put('/customers/{id}', [CustomerController::class, 'update']);

//Lead contoller

Route::post('/convert-lead', [LeadController::class, 'convertLead']);

//Import customer


Route::get('/import-customers', [CustomerImportController::class, 'showImportForm'])->name('import.customers.form');

Route::post('/import-customers', [CustomerImportController::class, 'import'])->name('import.customers');

//converted Leads
Route::get('/converted-leads', [ConvertedLeadsController::class, 'index'])->name('converted.leads');

//support page

Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support/saverevenue', [SupportController::class, 'store'])->name('support.store');







