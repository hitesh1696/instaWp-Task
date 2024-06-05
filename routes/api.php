<?php

use App\Http\Controllers\Api\ApiSignUpController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', [ApiSignUpController::class, 'signup']);
Route::post('/login', [ApiSignUpController::class, 'signin']);
Route::post('/logout', [ApiSignUpController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add-money', [WalletController::class, 'addMoney']);
    Route::post('/buy-cookie', [WalletController::class, 'buyCookie']);
});
