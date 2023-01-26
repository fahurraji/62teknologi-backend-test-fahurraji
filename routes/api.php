<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BussinessController;

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

// bussiness
Route::post('addBussiness','App\Http\Controllers\Api\MasterdataController@addBussiness')->middleware('auth:api');
Route::get('bussiness','App\Http\Controllers\Api\BussinessController@getBussiness')->middleware('auth:api');
Route::get('search','App\Http\Controllers\Api\BussinessController@search')->middleware('auth:api');


// master data category
Route::post('addCategory','App\Http\Controllers\Api\MasterdataController@addCategory')->middleware('auth:api');
Route::post('editCategory','App\Http\Controllers\Api\MasterdataController@editCategory')->middleware('auth:api');
Route::post('deleteCategory','App\Http\Controllers\Api\MasterdataController@deleteCategory')->middleware('auth:api');