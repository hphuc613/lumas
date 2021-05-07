<?php

use Illuminate\Support\Facades\Route;

Route::prefix('access-control')->group(function (){
    Route::get('/', 'PermissionController@index')
         ->name('get.access_control.index')
         ->middleware('can:permission-view');
    Route::post('/', 'PermissionController@postUpdate')
         ->name('post.access_control.update')
         ->middleware('can:permission-update');
});
