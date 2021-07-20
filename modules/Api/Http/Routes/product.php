<?php

use Illuminate\Support\Facades\Route;


Route::prefix('service')->group(function(){
    Route::get('list', 'ServiceController@list');
    Route::get('detail/{id}', 'ServiceController@detail');

    Route::middleware(['api-user'])->group(function(){
        Route::post('scan-use', 'ProductController@postUseProduct');
        Route::post('stop-use', 'ProductController@postStopUseProduct');
        Route::post('e-sign', 'ProductController@postESignProduct');
    });
});

Route::prefix('course')->group(function(){
    Route::get('list', 'CourseController@list');
    Route::get('detail/{id}', 'CourseController@detail');

    Route::middleware(['api-user'])->group(function(){
        Route::post('scan-use', 'ProductController@postUseProduct');
        Route::post('stop-use', 'ProductController@postStopUseProduct');
        Route::post('e-sign', 'ProductController@postESignProduct');
    });
});

