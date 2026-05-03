<?php
//Constructor
//Admin Unpack Modules
Route::group(['prefix'=>'modules','as'=>'modules_'],function (){
    Route::get('', [\App\Http\Controllers\Admin\ConstructController::class,'modulesList'])->name('');
    Route::post('/install', [\App\Http\Controllers\Admin\ConstructController::class,'modulesInstall'])->name('install');
});
//Admin Construct
Route::group(['prefix'=>'construct','as'=>'construct_'],function (){
    Route::get('/step1', [\App\Http\Controllers\Admin\ConstructController::class,'createMigration'])->name('create_module_1');
    Route::get('/step2', [\App\Http\Controllers\Admin\ConstructController::class,'createModel'])->name('create_module_2');
    Route::get('/step3', [\App\Http\Controllers\Admin\ConstructController::class,'createController'])->name('create_module_3');
    Route::post('/store1', [\App\Http\Controllers\Admin\ConstructController::class,'storeMigration'])->name('store_migration');
    Route::post('/store2', [\App\Http\Controllers\Admin\ConstructController::class,'storeModel'])->name('store_model');
    Route::post('/store3', [\App\Http\Controllers\Admin\ConstructController::class,'storeController'])->name('store_controller');
    Route::get('/delete', [\App\Http\Controllers\Admin\ConstructController::class,'deleteModule'])->name('delete_module');
    Route::delete('/destroy', [\App\Http\Controllers\Admin\ConstructController::class,'destroyModule'])->name('destroy_module');
});
///Constructor

