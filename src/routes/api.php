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

// 認証
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//習慣
Route::apiResource('routines', RoutineController::class)->except('index');

//記録
Route::apiResource('records', RecordController::class)->only(['store', 'destroy']);
