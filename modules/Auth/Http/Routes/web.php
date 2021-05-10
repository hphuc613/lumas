<?php

use Illuminate\Support\Facades\Route;

Route::get('/login.html', 'AuthMemberController@getLogin')->name('frontend.get.login.member');
Route::post('/login.html', 'AuthMemberController@postLogin')->name('frontend.post.login.member');
Route::get('/logout.html', 'AuthMemberController@logout')->name('frontend.get.logout.member');
Route::get('/forgot-password.html', 'AuthMemberController@forgotPassword')->name('frontend.get.forgot_password.member');
Route::post('/forgot-password.html', 'AuthMemberController@forgotPassword')->name('frontend.post.forgot_password.member');
