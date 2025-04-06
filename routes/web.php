<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Leads\LeadsController;
use App\Http\Controllers\Leads\LeadsHistoryController;
use App\Http\Controllers\Leads\LeadImportController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Customers\SupportController;
use App\Http\Controllers\Roles\RoleController;
#use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Users\UsersController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('logins');
Route::get('/no-access', function () {
    return view('errors.no-access');
})->name('no-access');

// Route::middleware(['auth:web'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//     Route::get('/leads', [LeadsController::class, 'index'])->name('leads.index');
//     Route::get('/import-customers', [LeadImportController::class, 'showImportForm'])->name('import.customers.form');
//     Route::post('/import-customers', [LeadImportController::class, 'import'])->name('import.customers');
//     Route::get('/customers', [CustomersController::class, 'index'])->name('converted.leads');
//     Route::get('/support', [SupportController::class, 'index'])->name('support.index');
//     Route::post('/support/saverevenue', [SupportController::class, 'store'])->name('support.store');

//     Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
//     Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
//     Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
//     Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
//     Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
//     Route::get('/roles/list', [RoleController::class, 'getRoles'])->name('roles.list');
//     Route::get('/roles/{id}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions.get');
//     Route::post('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

//     Route::prefix('users')->group(function () {
//         Route::get('/', [UsersController::class, 'index'])->name('users.index');
//         Route::get('/create', [UsersController::class, 'create'])->name('users.create');
//         Route::post('/store', [UsersController::class, 'store'])->name('users.store');
//         Route::get('/edit/{user}', [UsersController::class, 'edit'])->name('users.edit');
//         Route::post('/update/{user}', [UsersController::class, 'update'])->name('users.update'); // Changed to POST for AJAX
//         Route::delete('/delete/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
//     });

// });

Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard')->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/leads', [LeadsController::class, 'index'])->middleware('permission:leads')->name('leads.index');
    Route::get('/leads-history/{id}', [LeadsHistoryController::class, 'index'])->middleware('permission:leads')->name('leadshistory.index');
    Route::get('/import-customers', [LeadImportController::class, 'showImportForm'])->middleware('permission:import-customers')->name('import.customers.form');
    Route::post('/import-customers', [LeadImportController::class, 'import'])->middleware('permission:import-customers')->name('import.customers');
    Route::get('/customers', [CustomersController::class, 'index'])->middleware('permission:customers')->name('converted.leads');
    Route::get('/support', [SupportController::class, 'index'])->middleware('permission:support')->name('support.index');
    Route::post('/support/saverevenue', [SupportController::class, 'store'])->middleware('permission:support')->name('support.store');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:Roles')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:Roles')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:Roles')->name('roles.store');
    Route::post('/roles/{id}/update', [RoleController::class, 'update'])->middleware('permission:Roles')->name('roles.update');
    Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->middleware('permission:Roles')->name('roles.destroy');
    Route::get('/roles/list', [RoleController::class, 'getRoles'])->name('roles.list');
    Route::get('/roles/{id}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions.get');
    Route::post('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->middleware('permission:users')->name('users.index');
        Route::get('/create', [UsersController::class, 'create'])->middleware('permission:users')->name('users.create');
        Route::post('/store', [UsersController::class, 'store'])->middleware('permission:users')->name('users.store');
        Route::get('/edit/{user}', [UsersController::class, 'edit'])->middleware('permission:users')->name('users.edit');
        Route::post('/update/{user}', [UsersController::class, 'update'])->middleware('permission:users')->name('users.update');
        Route::delete('/delete/{user}', [UsersController::class, 'destroy'])->middleware('permission:users')->name('users.destroy');
    });
});