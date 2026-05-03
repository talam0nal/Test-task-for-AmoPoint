<?php
//User Portfolio
Route::get('/portfolios', 'PortfolioController@index')->middleware('maintenance')->name('portfolio_list');
Route::get('/portfolio/{oModel}', 'PortfolioController@view')->middleware('maintenance')->name('portfolio_show');