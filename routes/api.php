<?php

use App\Http\Controllers\GDriveController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/permohonan', [\App\Http\Controllers\PermohonanController::class, 'permohonan']);


// Retrieve all permohonan records
Route::get('/permohonan', [\App\Http\Controllers\PermohonanController::class, 'getAllPermohonan']);

// Retrieve a specific permohonan record by ID
Route::get('/permohonan/{id}', [\App\Http\Controllers\PermohonanController::class, 'getPermohonanById']);
Route::post('/permohonan/approv', [\App\Http\Controllers\PermohonanController::class, 'approvPermohonan']);
Route::post('/permohonan/reject', [\App\Http\Controllers\PermohonanController::class, 'rejectPermohonan']);


Route::post('generate', [\App\Http\Controllers\PermohonanController::class, 'generate']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
// });

// Route::group(['prefix' => '/surat'], function () {
//     Route::post('/permohonan', [\App\Http\Controllers\PermohonanController::class, 'store']);
// });

// Route::post('upload', [GDriveController::class, 'upload']);
Route::get('get-file', [GDriveController::class, 'getFile']);



