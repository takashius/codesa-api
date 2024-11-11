<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\PersonaController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AuthenticateJWT;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(AuthenticateJWT::class)->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('send-verification-email', [AuthController::class, 'sendVerificationEmail']);
    });
    Route::get('verify-email', [AuthController::class, 'verifyEmail']);
});


Route::middleware(AuthenticateJWT::class)->group(function () {
    Route::group(['prefix' => 'cards'], function () {
        Route::post('/recharge', [CardController::class, 'store']);
        Route::post('/recharges', [CardController::class, 'getData']);
    });
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('posts', PostController::class);

    Route::group(['prefix' => 'categories'], function () {
        Route::post('', [CategoryController::class, 'store']);
        Route::put('{category}', [CategoryController::class, 'update']);
        Route::delete('{category}', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'posts'], function () {
        Route::post('', [PostController::class, 'store']);
        Route::put('{post}', [PostController::class, 'update']);
        Route::delete('{post}', [PostController::class, 'destroy']);
    });
});

Route::post('verify_new_user', [PersonaController::class, 'getPersonByDocumentAndEmail']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{post}', [PostController::class, 'show']);
