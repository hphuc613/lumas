<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function(){
    Route::prefix('payment-method')->group(function(){
        Route::get('/', 'PaymentMethodController@index')->name('get.payment_method.list')
             ->middleware('can:payment-method');
        Route::group(['middleware' => 'can:payment-method-create'], function(){
            Route::get('/create', 'PaymentMethodController@getCreate')->name('get.payment_method.create');
            Route::post('/create', 'PaymentMethodController@postCreate')->name('post.payment_method.create');
        });

        Route::group(['middleware' => 'can:payment-method-update'], function(){
            Route::get('/update/{id}', 'PaymentMethodController@getUpdate')->name('get.payment_method.update');
            Route::post('/update/{id}', 'PaymentMethodController@postUpdate')->name('post.payment_method.update');
        });

        Route::get('/delete/{id}', 'PaymentMethodController@delete')->name('get.payment_method.delete')
             ->middleware('can:payment-method-delete');
    });
});
