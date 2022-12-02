<?php

use App\Http\Controllers\Api\AuthController;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::middleware(['auth:api'])->group(function () {
    Route::get('home', '\App\Http\Controllers\api\GoogleController@getWeather');
    Route::get('user', '\App\Http\Controllers\api\GoogleController@user');
    Route::get('logout', '\App\Http\Controllers\api\GoogleController@logout');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
 // return $request->user();
});

Route::get('google/login/url', '\App\Http\Controllers\api\GoogleController@getAuthUrl');
Route::post('google/login', '\App\Http\Controllers\api\GoogleController@GoogleLogin');
Route::post('login', '\App\Http\Controllers\api\GoogleController@login');


