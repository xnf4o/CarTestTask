<?php

use App\Http\Controllers\API\CarController;
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
Route::group(['prefix' => 'cars'], function () {
    Route::get('/', [CarController::class, 'allCars'])->name('cars.all');
    Route::get('/available', [CarController::class, 'availableCars'])->name('cars.available');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/user', [CarController::class, 'userCars'])->name('cars.user');
        Route::post('/rent/{id}', [CarController::class, 'rentCar'])->name('cars.rent');
        Route::post('/return', [CarController::class, 'returnOwnedCar'])->name('cars.return');
        Route::get('/history', [CarController::class, 'rentalHistory'])->name('cars.history');
    });
});

// User login
Route::post('/login', 'App\Http\Controllers\API\UserController@login')->name('login');
// User logout
Route::post('/logout', 'App\Http\Controllers\API\UserController@logout')->name('logout');
// User refresh
Route::post('/refresh', 'App\Http\Controllers\API\UserController@refresh')->name('refresh');
// User me
Route::get('/me', 'App\Http\Controllers\API\UserController@me')->name('me');
