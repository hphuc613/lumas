<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix('room')->group(function(){
        Route::get('/', 'RoomController@index')->name('get.room.list')->middleware('can:room');
        Route::group(['middleware' => 'can:room-create'], function(){
            Route::get('/create', 'RoomController@getCreate')->name('get.room.create');
            Route::post('/create', 'RoomController@postCreate')->name('post.room.create');
        });

        Route::group(['middleware' => 'can:room-update'], function(){
            Route::get('/update/{id}', 'RoomController@getUpdate')->name('get.room.update');
            Route::post('/update/{id}', 'RoomController@postUpdate')->name('post.room.update');
        });

        Route::get('/delete/{id}', 'RoomController@delete')->name('get.room.delete')->middleware('can:room-delete');
    });
});
