<?php

Route::post('/mail/send','MailController@sendMail')->name('send_mail');
Route::get('/mail/form','MailController@mailForm')->name('mail_form');