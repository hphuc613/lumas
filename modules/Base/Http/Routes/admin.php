<?php

use Illuminate\Support\Facades\Route;

Route::get('/change-locale/{key}', 'BaseController@changeLocale')->name('change_locale');

