<?php
//Admin Contact
Route::group(['prefix'=>'contact','as'=>'contact_'],function (){
    $sController = \App\Http\Controllers\Admin\ContactController::class;
    Route::get('', [$sController,'AdminIndex'])->name('');
    Route::get('/create', [$sController,'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [$sController,'AdminEdit'])->name('edit');
    Route::post('/store', [$sController,'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [$sController,'AdminPublic'])->name('public');
    Route::delete('/delete/{oModel}', [$sController,'AdminDelete'])->name('delete');

    //Admin Contact Category
    Route::group(['prefix'=>'category','as'=>'category_'],function (){
        $sController = \App\Http\Controllers\Admin\ContactCategoryController::class;
        Route::get('', [$sController,'AdminCategory'])->name('');
        Route::post('/store', [$sController,'AdminCategoryStore'])->name('store');
        Route::delete('/delete/{oModel}', [$sController,'AdminCategoryDelete'])->name('delete');
        Route::get('/public/{oModel}', [$sController,'AdminCategoryPublic'])->name('public');
    });
});