<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware'=>['custom.auth','expired.token']], function(){
    Route::get('user', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('customer/create', [CustomerController::class, 'store']);
    Route::get('customer/show', [CustomerController::class, 'show']);
    Route::delete('customer/destroy', [CustomerController::class, 'destroy']);
});


