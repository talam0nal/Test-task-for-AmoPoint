<?php
//User StaticPage
Route::get('/{oModel?}', 'StaticPageController@view')->middleware('maintenance')->name('static_page_show');
