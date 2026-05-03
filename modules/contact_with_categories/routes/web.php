<?php
//User Contact
Route::get('/contacts', 'ContactController@index')->middleware('maintenance')->name('contact_list');
Route::get('/contact/{oModel}', 'ContactController@view')->middleware('maintenance')->name('contact_show');