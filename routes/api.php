<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DetailPengingat;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PengingatController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::post('reset-password', [AuthController::class, 'passwordReset']);

Route::post('ceklogin/{email}', [AuthController::class, 'cekLogin']);
Route::get('reset-password/{email}', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('verifikasi-akun/{email}', [AuthController::class, 'verifikasiakun']);
    Route::post('update-password', [AuthController::class, 'passwordUpdate']);
    Route::get('list-pengingat', [PengingatController::class, 'index']);
    // Route::get('listUserPengingat/{pengingat_id}', [PengingatController::class, 'listUserPengingat']);
    Route::get('listUserPengingat/{pengingat_id}', [PengingatController::class, 'listUserPengingat']);
    Route::get('daftarUserPengingat/{pengingat_id}/{auth_id}', [PengingatController::class, 'daftarUserPengingat']);
    Route::get('pengingat-list/{id}', [PengingatController::class, 'pengingatList']);
    Route::post('create-pengingat', [PengingatController::class, 'store']);
    Route::post('edit-pengingat/{pengingat_id}', [PengingatController::class, 'update']);
    Route::delete('delete-pengingat/{id}', [PengingatController::class, 'destroy']);

    Route::get('list-kegiatan', [DetailPengingat::class, 'index']);
    Route::get('list-kegiatan/{id}', [DetailPengingat::class, 'list']);
    Route::post('create-kegiatan', [DetailPengingat::class, 'store']);
    Route::post('update-kegiatan/{id}', [DetailPengingat::class, 'update']);
    Route::post('update-ceklis-kegiatan/{id}', [DetailPengingat::class, 'updateCeklist']);
    Route::post('delete-kegiatan/{id}', [DetailPengingat::class, 'destroy']);

    Route::get('list-friends/{auth_id}', [FriendController::class, 'index']);
    Route::post('add-friends/{authId}', [FriendController::class, 'addFreiend']);
    Route::post('delete-friends/{id}', [FriendController::class, 'destroy']);
});
