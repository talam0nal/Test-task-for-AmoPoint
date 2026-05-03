<?php
//User User
Route::get('/user/login', 'UserController@login')->middleware(['guest','maintenance'])->name('user_login');
Route::get('/user/registration', 'UserController@registration')->middleware(['guest','maintenance'])->name('user_registration');