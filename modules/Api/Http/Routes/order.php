<?php


use Illuminate\Support\Facades\Route;

Route::prefix('order')->group(function(){
    Route::get('/', 'OrderController@list');
    Route::get('detail/{id}', 'OrderController@detail');
});
