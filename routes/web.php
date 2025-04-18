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
use App\Http\Controllers\Email\EmailTemplateController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('logins');
Route::get('/no-access', function () {
    return view('errors.no-access');
})->name('no-access');
Route::get('/leads/create', [LeadsController::class, 'create'])->name('leads.create');
Route::post('/leads', [LeadsController::class, 'store'])->name('leads.store');

Route::get('/test-email', [UsersController::class, 'testEmail']);

Route::middleware(['auth:web'])->group(function () {

    //Dashbaord contoller
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard')->name('dashboard');
    Route::get('/dashboard/filter', [DashboardController::class, 'filter']);
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Existing index route
    Route::get('/leads', [LeadsController::class, 'index'])->middleware('permission:leads')->name('leads.index');

    //assign to routes
    
    Route::get('/users/sales-admins', [LeadsController::class, 'getSalesAdmins'])->name('users.sales-admins');

    Route::post('/leads/assign', [LeadsController::class, 'assign'])->name('leads.assign');

    // New show route for fetching a single lead

    Route::get('/leads/{lead}', [LeadsController::class, 'viewfulldetails'])->middleware('permission:leads')->name('leads.viewfulldetails');

    Route::get('/leadstatus/{lead}', [LeadsController::class, 'show'])->middleware('permission:leads')->name('leads.show');

    Route::get('/leads-history/{id}', [LeadsHistoryController::class, 'index'])->middleware('permission:leads')->name('leadshistory.index');

    
    
    Route::get('/import-customers', [LeadImportController::class, 'showImportForm'])->middleware('permission:import-customers')->name('import.customers.form');

    Route::post('/update-status/{lead}', [LeadsController::class, 'updateStatus'])->middleware('permission:leads')->name('leads.update.status');

    Route::delete('/leads/{lead}', [LeadsController::class, 'destroy'])->middleware('permission:leads')->name('leads.destroy');

    // Route::post('/convert-lead', [LeadsController::class, 'convertLead'])->middleware('permission:leads')->name('leads.convert');

    Route::get('/leads/{lead}/edit', [LeadsController::class, 'edit'])->middleware('permission:leads')->name('leads.edit');

    Route::put('/leads/{lead}', [LeadsController::class, 'update'])->middleware('permission:leads')->name('leads.update');

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

    Route::get('/profile/edit', [UsersController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UsersController::class, 'updateProfile'])->name('profile.update');

    Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/create', [EmailTemplateController::class, 'create'])->name('email-templates.create');
    Route::post('/email-templates/save', [EmailTemplateController::class, 'store'])->name('email-templates.store');
    Route::get('/email-templates/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::put('/email-templates/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::delete('/email-templates/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');

    Route::get('/', function () {
    return redirect('/send-email'); // Redirect root to the email form
});

Route::get('/', function () {
    return redirect('/send-email');
});

Route::get('/send-email', [EmailTemplateController::class, 'showSendEmailForm'])->name('send-email');
Route::post('/send-email', [EmailTemplateController::class, 'sendEmail'])->name('send-email.post');
Route::post('/store-group', [EmailTemplateController::class, 'storeGroup'])->name('store-group');
Route::patch('/update-group/{group}', [EmailTemplateController::class, 'updateGroup'])->name('update-group');
Route::delete('/delete-group/{group}', [EmailTemplateController::class, 'deleteGroup'])->name('delete-group');
Route::get('/sent-emails', [EmailTemplateController::class, 'showSentEmails'])->name('sent-emails');
Route::get('/get-template/{id}', [EmailTemplateController::class, 'getTemplate'])->name('get.template');
Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
Route::get('/email-templates/create', [EmailTemplateController::class, 'create'])->name('email-templates.create');
Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.update');
Route::get('/email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
Route::put('/email-templates/{template}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
Route::delete('/email-templates/{template}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');
});