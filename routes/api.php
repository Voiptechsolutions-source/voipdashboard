<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/leadsubmit', [CustomerController::class, 'store']);
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!'], 200);
});