<?php

use Illuminate\Support\Facades\Route;

Route::get('documentation', 'DocumentationController@getDocumentation')->name('documentation');
Route::get('documentation-mobile', 'DocumentationMobileController@getDocumentation')->name('documentation_mobile');
