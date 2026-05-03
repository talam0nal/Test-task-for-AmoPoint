<?php
//User StaticPage
Route::get('/static_pages', 'StaticPageController@index')->middleware('maintenance')->name('static_page_list');
Route::get('/{oModel}', 'StaticPageController@view')->middleware('maintenance')->name('static_page_show');