<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix('role')->group(function(){
        Route::get('/', 'RoleController@index')->name('get.role.list')->middleware('can:roles');
        Route::group(['middleware' => 'can:role-create'], function(){
            Route::get('/create', 'RoleController@getCreate')->name('get.role.create');
            Route::post('/create', 'RoleController@postCreate')->name('post.role.create');
        });

        Route::group(['middleware' => 'can:role-create'], function(){
            Route::get('/update/{id}', 'RoleController@getUpdate')->name('get.role.update');
            Route::post('/update/{id}', 'RoleController@postUpdate')->name('post.role.update');
        });

        Route::get('/delete/{id}', 'RoleController@delete')->name('get.role.delete')->middleware('can:role-delete');
    });
});
