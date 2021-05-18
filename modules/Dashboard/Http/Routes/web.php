<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['member'])->group(function(){
    Route::get('/', 'FrontendDashboardController@index')->name('frontend.dashboard');
});

/*Route::get('/', function(){
  return redirect()->route('dashboard');
})->name('frontend.dashboard');*/
