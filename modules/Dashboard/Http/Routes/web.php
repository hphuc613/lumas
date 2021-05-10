<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['member'])->group(function(){
    Route::get('/', 'FrontendDashboardController@index')->name('frontend.dashboard');
});
