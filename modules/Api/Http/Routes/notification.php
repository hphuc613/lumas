<?php

use Illuminate\Support\Facades\Route;

Route::get('test', 'NotificationController@test');

Route::prefix('notification')->group(function(){
    Route::get('list', 'NotificationController@getList');

    /** Notification of User */
    Route::get('user-next', 'NotificationController@getUserNext')->middleware(['api-user']);

    /** Notification of Client */
    Route::get('client-next', 'NotificationController@getMemberNext')->middleware(['api-member']);

    Route::post('read-notification/{apm_id}/{user_id}/{user_mobile}', 'NotificationController@postReadNotification');
});
