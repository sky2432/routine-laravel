<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ユーザー
Route::put('users/{user_id}/password', [UserController
::class, 'updatePassword'])->name('users.password.update');
Route::apiResource('users', UserController::class)->except(['index', 'show']);

Route::group([
    'middleware' => ['auth:api'],
], function () {
    // 認証
    Route::group([
    'prefix' => 'auth'
  ], function () {
      Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
      Route::post('logout', [AuthController::class, 'logout']);
      Route::post('refresh', [AuthController::class, 'refresh']);
      Route::get('user', [AuthController::class, 'me']);
  });
    // 記録
    Route::apiResource('records', RecordController::class)->only(['store', 'destroy']);

    // 習慣
    Route::apiResource('routines', RoutineController::class)->except('index');
});
