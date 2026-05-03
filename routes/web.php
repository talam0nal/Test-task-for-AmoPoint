<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\LotteryController;
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
Auth::routes();

Route::get('/user/login', 'UserController@login')->middleware(['guest','maintenance'])->name('user_login');
Route::get('/user/registration', 'UserController@registration')->middleware(['guest','maintenance'])->name('user_registration');

Route::post('/{token}/webhook', [TelegramController::class, 'webhook']);

Route::get('/setwebhook', [TelegramController::class, 'setWebhook']);

Route::get('/testmessage', [TelegramController::class, 'testMessage']);

Route::get('/tg-usernames', [TelegramController::class, 'getUsernames']);

//Route::get('/set-cities', [QuestController::class, 'setCities']);

//Route::get('/make-tickets', [LotteryController::class, 'makeTickets']);

Route::get('/set-cities-for-lotteries', [LotteryController::class, 'setCities']);
