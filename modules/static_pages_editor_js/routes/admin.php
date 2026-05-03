<?php
//Admin StaticPage
Route::group(['prefix'=>'static_page','as'=>'static_page_'],function () {
    $sController = \App\Http\Controllers\Admin\StaticPageController::class;
    Route::match(['get', 'post'],'', [$sController,'AdminIndex'])->name('');
    Route::get('/create', [$sController,'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [$sController,'AdminEdit'])->name('edit');
    Route::post('/store', [$sController,'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [$sController,'AdminPublic'])->name('public');
    Route::post('/edit_text/{oModel}', [$sController,'AdminEditText'])->name('edit_text');
    Route::delete('/delete/{oModel}', [$sController,'AdminDelete'])->name('delete');
});
