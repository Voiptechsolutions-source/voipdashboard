<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Leads\LeadsApiController; // Updated namespace
use App\Http\Controllers\Webhook\GoogleWebhookController;
use App\Http\Controllers\Webhook\FacebookWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/savelead', [LeadsApiController::class, 'savecustomerlead']);

Route::post('/webhook/google-ads', [GoogleWebhookController::class, 'handleGoogleWebhook']);

Route::get('/facebook-webhook', [FacebookWebhookController::class, 'handleFacebookWebhook']);
Route::post('/facebook-webhook', [FacebookWebhookController::class, 'handleFacebookWebhook']);



