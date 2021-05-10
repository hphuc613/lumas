<?php

use Illuminate\Support\Facades\Route;

Route::get('/login.html', 'AuthMemberController@getLogin')->name('frontend.get.login.member');
Route::post('/login.html', 'AuthMemberController@postLogin')->name('frontend.get.login.member');
Route::get('/logout.html', 'AuthMemberController@logout')->name('frontend.get.logout.member');
