<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\MahasiswaController;


Route::get("/", function () {
    return response()->json(['message' => 'Hello World!'], 200);
});

Route::post("/auth/login", [\App\Http\Controllers\API\AuthController::class, "login"]);
Route::post("/auth/register", [\App\Http\Controllers\API\AuthController::class, "register"]);

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    //data mahasiswa routes
    Route::get('/mahasiswa/get_all_data_mahasiswa', [AdminController::class, 'GetAllDataMahasiswa']);
    Route::get('/mahasiswa/get_mahasiswa/{id}', [AdminController::class, 'GetMahasiswaById']);
    Route::post('/mahasiswa/add_data_mahasiswa', [AdminController::class, 'CreateDataMahasiswa']);
    Route::put('/mahasiswa/update_data_mahasiswa/{id}', [AdminController::class, 'UpdateDataMahasiswa']);
    Route::delete('/mahasiswa/delete_data_mahasiswa/{id}', [AdminController::class, 'DeleteDataMahasiswa']);
});

Route::prefix('mahasiswa')->middleware(['auth:sanctum', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard']);
});
