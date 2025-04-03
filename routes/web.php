<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Leads\LeadsController;
use App\Http\Controllers\Leads\LeadController;
use App\Http\Controllers\Leads\LeadImportController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Customers\SupportController;
use App\Http\Controllers\Admin\DeleteController;



// Show login form at the root URL
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Handle the login form submission
Route::post('/login', [AuthController::class, 'login'])->name('logins');
Route::middleware(['auth:web'])->group(function () {
    // Dashboard route, protected by auth middleware
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

    Route::get('/dashboard/filter', [DashboardController::class, 'filter']);

    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData']);

    // Handle logout
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


    // Customer routes
    Route::get('/leads', [LeadsController::class, 'index'])->name('leads.index');

    Route::get('/leads/{id}', [LeadsController::class, 'show'])->name('leads.show');

    Route::post('/update-status/{id}', [LeadsController::class, 'updateStatus'])->name('update.status');

    Route::delete('/leads/{id}', [LeadsController::class, 'destroy']);


    Route::get('/leads/{id}/edit', [LeadsController::class, 'edit']);

    Route::put('/leads/{id}', [LeadsController::class, 'update']);

    //Lead contoller

    //Route::post('/convert-lead', [LeadController::class, 'convertLead']);

    //Import customer


    Route::get('/import-customers', [LeadImportController::class, 'showImportForm'])->name('import.customers.form');

    Route::post('/import-customers', [LeadImportController::class, 'import'])->name('import.customers');

    //converted Leads
    Route::get('/customers', [CustomersController::class, 'index'])->name('converted.leads');

    //Route::post('/convert-lead', [LeadController::class, 'convertLead']);

    

    //support page

    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support/saverevenue', [SupportController::class, 'store'])->name('support.store');


});




