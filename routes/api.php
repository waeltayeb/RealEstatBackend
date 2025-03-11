<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HouseController;

use App\Http\Controllers\PropertyController;




Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);
Route::get('/houses', [HouseController::class, 'getHouses']);


Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('user', [AuthController::class, 'user']);

    Route::post('/addproperty', [HouseController::class, 'store']);

});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
