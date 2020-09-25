<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('events', EventController::class)->only(['index', 'show', 'store', 'update']);
Route::resource('clients', ClientController::class)->only(['index', 'show', 'store', 'update']);
Route::resource('reservations', ReservationController::class)->only(['index', 'show', 'store']);
Route::get('reservations/ByClient/{document}', 'App\Http\Controllers\ReservationController@listReservationsClientDocument')->name('reservationsByClient');
