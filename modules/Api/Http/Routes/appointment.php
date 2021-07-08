<?php

use Illuminate\Support\Facades\Route;


Route::prefix('appointment')->group(function(){
    Route::get('list', 'AppointmentController@list');

    Route::get('detail/{id}', 'AppointmentController@detail');
});
