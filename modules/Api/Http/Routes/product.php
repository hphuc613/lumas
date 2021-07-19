<?php

use Illuminate\Support\Facades\Route;


Route::prefix('service')->group(function(){
    Route::get('list', 'ServiceController@list');
    Route::get('detail/{id}', 'ServiceController@detail');
});

Route::prefix('course')->group(function(){
    Route::get('list', 'CourseController@list');
    Route::get('detail/{id}', 'CourseController@detail');
});
