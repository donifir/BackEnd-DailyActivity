<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('verifikasi/{link}', [AuthController::class, 'verifikasiview']);
Route::get('verifikasi-akun/link', [AuthController::class, 'verifikasiakun']);
Route::get('verifikasi-akun/{email}', [AuthController::class, 'verifikasiakun']);
Route::get('reset-password/{email}/{link}', [AuthController::class, 'cekResetPassword']);
Route::post('store/reset-password/{email}', [AuthController::class, 'storeResetPassword']);
Route::post('store/reset-password/{email}', [AuthController::class, 'storeResetPassword']);
Route::get('selesai', [AuthController::class, 'storeResetPassword']);
