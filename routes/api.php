<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\MahasiswaController;


Route::get("/", function () {
    return response()->json(['message' => 'Hello World!'], 200);
});

Route::get("/login", [\App\Http\Controllers\API\AuthController::class, "login"]);
Route::get("/register", [\App\Http\Controllers\API\AuthController::class, "register"]);

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});

Route::prefix('mahasiswa')->middleware(['auth:sanctum', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard']);
});
