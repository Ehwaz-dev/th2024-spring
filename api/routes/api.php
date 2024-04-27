<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
});

Route::resource('events', \App\Http\Controllers\Api\EventController::class)->only([
    'index',
    'store',
    'update',
    'show',
    'destroy',
]);
Route::prefix('events')->group(function () {
    Route::post('/{event}/join', [\App\Http\Controllers\Api\EventController::class, 'join']);
    Route::post('/{event}/accept', [\App\Http\Controllers\Api\EventController::class, 'accept']);
    Route::post('/{event}/like', [\App\Http\Controllers\Api\EventController::class, 'like']);
    Route::post('/{event}/comment', [\App\Http\Controllers\Api\EventController::class, 'comment']);
});

Route::resource('articles', \App\Http\Controllers\Api\ArticleController::class)->only([
    'index',
    'store',
    'update',
    'show',
    'destroy',
]);
