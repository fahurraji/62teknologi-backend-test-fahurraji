<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// post register user
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
// post login
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
// get user
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
