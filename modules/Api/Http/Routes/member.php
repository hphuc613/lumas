<?php

use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function(){
    Route::post('login', 'MemberController@login');
    Route::post('register-validate', 'MemberController@validateRegister');
    Route::post('register', 'MemberController@register');
    Route::post('forgot-password-validate', 'MemberController@validateForgotPassword');
    Route::post('forgot-password', 'MemberController@forgotPassword');

    Route::middleware(['api-member'])->group(function(){
        Route::post('logout', 'MemberController@logout');
        Route::get('profile', 'MemberController@profile');
        Route::post('profile-update', 'MemberController@updateProfile');
        Route::post('update-avatar', 'MemberController@updateAvatar');
    });

    Route::prefix('service')->group(function(){
        Route::get('list/{member_id}', 'MemberController@getServiceList');
        Route::get('detail/{id}', 'MemberController@getServiceDetail');
    });

    Route::prefix('course')->group(function(){
        Route::get('list/{member_id}', 'MemberController@getCourseList');
        Route::get('detail/{id}', 'MemberController@getCourseDetail');
    });
});
