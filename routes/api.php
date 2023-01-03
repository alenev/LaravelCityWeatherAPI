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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::middleware(['auth:api'])->group(function () {
    // Route::get('home', '\App\Http\Controllers\Api\GoogleController@getWeather');
    Route::get('city_weather', '\App\Http\Controllers\Api\CityWeatherController@index');
    Route::get('user', '\App\Http\Controllers\Api\GoogleController@user');
    Route::get('logout', '\App\Http\Controllers\Api\GoogleController@logout');
    
});


//Route::get('google/login/url', '\App\Http\Controllers\Api\GoogleController@getAuthUrl');
//Route::post('login', '\App\Http\Controllers\Api\GoogleController@login');


Route::get('google/login/url', '\App\Http\Controllers\Api\GoogleAuthUrlController@getGoogleAuthUrl');
Route::post('auth', '\App\Http\Controllers\Api\AuthController@auth');


 





