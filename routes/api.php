<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\PersonaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('send-verification-email', [AuthController::class, 'sendVerificationEmail']);
    });
    Route::get('verify-email', [AuthController::class, 'verifyEmail']);
});
Route::middleware(['auth.jwt'])->group(function () {
    Route::group(['prefix' => 'cards'], function () {
        Route::post('/recharge', [CardController::class, 'store']);
        Route::post('/recharges', [CardController::class, 'getData']);
    });
});

Route::post('verify_new_user', [PersonaController::class, 'getPersonByDocumentAndEmail']);
