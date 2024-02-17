<?php

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

//* auth:guard

Route::post('/register',[RegisterController::class, 'store']); 
Route::post('/login',[AuthenticatedSessionController::class, 'login']); 

Route::get('/user', function () {
    // Mendapatkan user yang sedang terautentikasi
    $user = Auth::user();
    return $user;
})->middleware('auth:sanctum');