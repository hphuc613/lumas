<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/403.html', 'DashboardController@errorPage')->name('error.page');
});
