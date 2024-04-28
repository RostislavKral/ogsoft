<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkDayController;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/workday', [WorkDayController::class, 'isWorkDay']);
Route::post('/approximate', [TaskController::class, 'approximate']);
