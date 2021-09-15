<?php

use Illuminate\Support\Facades\Route;

Route::get('documentation', 'DocumentationController@getDocumentation')->name('documentation');
Route::get('documentation-mobile', 'DocumentationMobileController@getDocumentation')->name('documentation_mobile');
Route::get('documentation-ct', 'DocumentationWebCTController@getDocumentation')->name('documentation_ct');
Route::get('documentation-mobile-ct', 'DocumentationMobileCTController@getDocumentation')
     ->name('documentation_mobile_ct');
