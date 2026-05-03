<?php


//User Blog
Route::get('/blogs', 'BlogController@index')->middleware('maintenance')->name('blog_list');
Route::get('/blog/{oModel}', 'BlogController@view')->middleware('maintenance')->name('blog_show');
