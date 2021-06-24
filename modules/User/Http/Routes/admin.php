<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix('user')->group(function(){
        Route::get('/', 'UserController@index')->name('get.user.list')->middleware('can:users');
        Route::get('/appointment/{id}', 'UserController@getAppointment')->name('get.user.appointment')
             ->middleware('can:users');
        Route::group(['middleware' => 'can:user-create'], function(){
            Route::get('/create', 'UserController@getCreate')->name('get.user.create');
            Route::post('/create', 'UserController@postCreate')->name('post.user.create');
        });
        Route::group(['middleware' => 'can:user-update'], function(){
            Route::get('/update/{id}', 'UserController@getUpdate')->name('get.user.update');
            Route::post('/update/{id}', 'UserController@postUpdate')->name('post.user.update');
            Route::post('/update-status', 'UserController@postUpdateStatus')->name('post.user.update_status');
            Route::get('/update-salary/{id}', 'UserController@getUpdateSalary')->name('get.salary.update');
            Route::post('/update-salary/{id}', 'UserController@postUpdateSalary')->name('post.salary.update');
            Route::get('/single-reload-salary/{id}', 'UserController@singleReloadSalary')
                 ->name('get.salary.single_reload');
            Route::get('/bulk-reload-salary', 'UserController@bulkReloadSalary')
                 ->name('get.salary.bulk_reload');
        });
        Route::get('/delete/{id}', 'UserController@delete')->name('get.user.delete')->middleware('can:user-delete');
    });

    //Profile admin routes
    Route::get('/profile', 'UserController@getProfile')->name('get.profile.update');
    Route::post('/profile', 'UserController@postProfile')->name('post.profile.update');
    Route::get('/salary/{id}', 'UserController@getSalary')->name('get.profile.salary');
});
