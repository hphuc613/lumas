<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix('instrument')->group(function(){
        Route::get('/', 'InstrumentController@index')->name('get.instrument.list')->middleware('can:instrument');
        Route::group(['middleware' => 'can:instrument-create'], function(){
            Route::get('/create', 'InstrumentController@getCreate')->name('get.instrument.create');
            Route::post('/create', 'InstrumentController@postCreate')->name('post.instrument.create');
        });

        Route::group(['middleware' => 'can:instrument-update'], function(){
            Route::get('/update/{id}', 'InstrumentController@getUpdate')->name('get.instrument.update');
            Route::post('/update/{id}', 'InstrumentController@postUpdate')->name('post.instrument.update');
        });

        Route::get('/delete/{id}', 'InstrumentController@delete')->name('get.instrument.delete')
             ->middleware('can:instrument-delete');
    });
});
