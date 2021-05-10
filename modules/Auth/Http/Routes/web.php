<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'AuthMemberController@getLogin')->name('frontend.get.login.member');
Route::post('/login', 'AuthMemberController@postLogin')->name('frontend.post.login.member');
Route::get('/logout', 'AuthMemberController@logout')->name('frontend.get.logout.member');
Route::get('/forgot-password', 'AuthMemberController@forgotPassword')->name('frontend.get.forgot_password.member');
Route::post('/forgot-password', 'AuthMemberController@forgotPassword')->name('frontend.post.forgot_password.member');

Route::get('/signup', 'AuthMemberController@getSignUp')->name('frontend.get.signup.member');
Route::post('/signup', 'AuthMemberController@postSignUp')->name('frontend.post.signup.member');
