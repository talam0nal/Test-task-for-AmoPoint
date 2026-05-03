<?php
/**
 * Created by PhpStorm.
 * User: Tiberium
 * Date: 05.04.2021
 * Time: 17:54
 */
Route::get('download/{oMessage}','ChatController@downloadMessageFile')->middleware(['maintenance','auth'])->name('download');
Route::post('/chat/send','ChatController@sendMessage')->middleware(['maintenance','auth'])->name('send_chat_message');
Route::post('/chat/messages','ChatController@getDialog')->middleware(['maintenance','auth'])->name('chat_get_messages');
Route::post('/chat/ban','ChatController@ban')->middleware(['maintenance','auth'])->name('chat_ban_dialog');