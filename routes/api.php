<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\XmlController;
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

Route::controller(SoapController::class)->group(function () {
    Route::get('pokemon/search', 'search');
});

Route::controller(XmlController::class)->group(function () {
    Route::post('xsd', 'xsd');
    Route::post('rng', 'rng');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'loginError')->name('login');
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
