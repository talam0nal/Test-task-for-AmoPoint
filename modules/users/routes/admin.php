<?php
//Admin User
Route::group(['prefix'=>'user','as'=>'user_'],function () {
    $sController = \App\Http\Controllers\Admin\UserController::class;
    Route::match(['get', 'post'],'', [$sController,'AdminIndex'])->name('');
    //Route::get('/create', [$sController,'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [$sController,'AdminEdit'])->name('edit');
    Route::post('/store', [$sController,'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [$sController,'AdminPublic'])->name('public');
    Route::delete('/delete/{oModel}', [$sController,'AdminDelete'])->name('delete');
});
