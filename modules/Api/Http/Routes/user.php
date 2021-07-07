<?php

use Illuminate\Support\Facades\Route;

Route::get('test', 'ApiController@test');

Route::prefix('user')->group(function(){
    Route::post('login', 'UserController@login');
    Route::post('forgot-password', 'UserController@forgotPassword');

    Route::middleware(['api-user'])->group(function(){
        Route::post('logout', 'UserController@logout');
        Route::get('profile', 'UserController@profile');
    });
});