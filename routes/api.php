<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\ShopItemController;

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

//Города
Route::group(['prefix'=>'city'], function() {
    Route::get('/list', [CityController::class,'getList']);
    Route::group(['middleware'=> ['auth:api']],function() {
        Route::post('/required', [CityController::class, 'required']);
    });
});
