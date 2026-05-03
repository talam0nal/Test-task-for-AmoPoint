<?php
//User Reviews
Route::get('/reviews', 'ReviewController@index')->middleware('maintenance')->name('review_list');
Route::post('/review/store', 'ReviewController@store')->middleware('maintenance')->name('review_store');