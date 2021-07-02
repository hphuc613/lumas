<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/change-locale/{key}', 'BaseController@changeLocale')->name('change_locale');

Route::get('/lalala', function(Request $request){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    $request->session()->flash('success', trans("Cache is cleared"));
    return redirect()->back();
});

Route::get('/notify-appointment', function(){
    Artisan::call('notify:appointments');
});
