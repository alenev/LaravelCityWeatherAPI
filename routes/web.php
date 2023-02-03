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


Route::get('/', '\App\Http\Controllers\HomeController@index')->name('index');
Route::get('/login', '\App\Http\Controllers\HomeController@login')->name('login');
Route::get('/email/verify/{id}/{hash}', '\App\Http\Controllers\VerifyEmailController@set')->name('verification.verify');
Route::get('/email_verify_success', '\App\Http\Controllers\HomeController@emailVerifySuccess')->name('verification.success');

// Route::get('/vuedemo', function () {
//     return Redirect::to('/vuedemo/public');
// });


