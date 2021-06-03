<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/read-notification/{id}', 'NotificationController@readNotification')->name('read_notification');
});
