<?php

use Illuminate\Support\Facades\Route;

Route::get('test', 'ApiController@test');

Route::prefix('client')->group(function(){
    Route::post('login', 'MemberController@login');
    Route::post('register-validate', 'MemberController@validateRegister');
    Route::post('register', 'MemberController@register');
    Route::post('forgot-password', 'MemberController@forgotPassword');

    Route::middleware(['api-member'])->group(function(){
        Route::post('logout', 'MemberController@logout');
        Route::get('profile', 'MemberController@profile');
        Route::post('profile-update', 'MemberController@updateProfile');
    });
});