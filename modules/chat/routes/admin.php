<?php
//Admin Dialog
Route::group(['prefix'=>'dialog','as'=>'dialog_'],function (){
    $sController = \App\Http\Controllers\Admin\DialogController::class;
    Route::get('', [$sController,'AdminIndex'])->name('');
    Route::get('/create', [$sController,'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [$sController,'AdminEdit'])->name('edit');
    Route::post('/store', [$sController,'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [$sController,'AdminPublic'])->name('public');
    Route::delete('/delete/{oModel}', [$sController,'AdminDelete'])->name('delete');
});

//Admin Message
Route::group(['prefix'=>'message','as'=>'message_'],function (){
    $sController = \App\Http\Controllers\Admin\MessageController::class;
    Route::get('', [$sController,'AdminIndex'])->name('');
    Route::get('/create', [$sController,'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [$sController,'AdminEdit'])->name('edit');
    Route::post('/store', [$sController,'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [$sController,'AdminPublic'])->name('public');
    Route::delete('/delete/{oModel}', [$sController,'AdminDelete'])->name('delete');
});
