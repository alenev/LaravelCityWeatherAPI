<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('loginVUE');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    });
});

Route::get('/email/verify/{id}/{hash}', '\App\Http\Controllers\VerifyEmailController@set')->name('verification.verify');


Route::get('/email_verify_success', function () {
    return view('email_verify_success');
});

Route::get('/vuedemo', function () {
    return Redirect::to('/vuedemo/public');
   // return File::get(env('APP_URL').'/vuedemo/public/index.html');
});


