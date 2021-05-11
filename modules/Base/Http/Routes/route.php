<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/change-locale/{key}', 'BaseController@changeLocale')->name('change_locale');

Route::get('/clear-cache-lalala', function(Request $request){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    $request->session()->flash('success', trans("Cache is cleared"));
    return redirect()->back();
});
