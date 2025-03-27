<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerApiController; // Updated namespace
use App\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/savelead', [CustomerApiController::class, 'savecustomerlead']);
Route::post('/webhook/google-ads', [WebhookController::class, 'handle']);
Route::get('/facebook-webhook', [WebhookController::class, 'facebookWebhook']);
Route::post('/facebook-webhook', [WebhookController::class, 'facebookWebhook']);



