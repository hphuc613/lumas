<?php

use Illuminate\Support\Facades\Route;

Route::get('documentation', 'DocumentationController@getDocumentation')->name('documentation');
Route::get('documentation-mobile', 'DocumentationMobileController@getDocumentation')->name('documentation_mobile');
Route::get('documentation-ct', 'DocumentationWebCTController@getDocumentation')->name('documentation_ct');
Route::get('documentation-mobile-ct', 'DocumentationMobileCTController@getDocumentation')
     ->name('documentation_mobile_ct');
Route::get('guide-client', 'GuideClientController@getDocumentation')->name('guide_client');
Route::get('guide-client-ct', 'GuideClientTCController@getDocumentation')->name('guide_client_ct');
Route::get('guide-staff', 'GuideStaffController@getDocumentation')->name('guide_staff');
Route::get('guide-staff-ct', 'GuideStaffTCController@getDocumentation')->name('guide_staff_ct');
