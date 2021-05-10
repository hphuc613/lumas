<?php

use Illuminate\Support\Facades\Route;
Route::prefix('admin')->group(function(){
    Route::get('/login.html', 'AuthController@getLogin')->name('get.login.admin');
    Route::post('/login.html', 'AuthController@postLogin')->name('post.login.admin');
    Route::get('/logout.html', 'AuthController@logout')->name('get.logout.admin');
    Route::get('/forgot-password', 'AuthController@forgotPassword')->name('get.logout.forgot_password');
    Route::post('/forgot-password', 'AuthController@forgotPassword')->name('post.logout.forgot_password');
});
