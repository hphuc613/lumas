<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix('role')->group(function(){
        Route::get('/', 'RoleController@index')->name('get.role.list')->middleware('can:roles');
        Route::group(['middleware' => 'can:role-create'], function(){
            Route::get('/create', 'RoleController@getCreate')->name('get.role.create');
            Route::post('/create', 'RoleController@postCreate')->name('post.role.create');
        });

        Route::group(['middleware' => 'can:role-update'], function(){
            Route::get('/update/{id}', 'RoleController@getUpdate')->name('get.role.update');
            Route::post('/update/{id}', 'RoleController@postUpdate')->name('post.role.update');
        });

        Route::get('/delete/{id}', 'RoleController@delete')->name('get.role.delete')->middleware('can:role-delete');
    });

    Route::prefix('commission-rate')->group(function(){
        Route::group(['middleware' => 'can:role-create'], function(){
            Route::get('/create/{role_id}', 'CommissionRateController@getCreate')->name('get.commission_rate.create');
            Route::post('/create/{role_id}', 'CommissionRateController@postCreate')
                 ->name('post.commission_rate.create');
            Route::get('/update/{id}', 'CommissionRateController@getUpdate')->name('get.commission_rate.update');
            Route::post('/update/{id}', 'CommissionRateController@postUpdate')->name('post.commission_rate.update');
            Route::get('/delete/{id}', 'CommissionRateController@delete')->name('get.commission_rate.delete');
        });
    });
});
