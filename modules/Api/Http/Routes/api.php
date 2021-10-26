<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function(){

    Route::get('helper-center', 'ApiController@getHelperCenter');

    /** User API */
    include 'user.php';

    /** Member API */
    include 'member.php';

    /** Appointment API */
    include 'appointment.php';

    /** Product API */
    include 'product.php';

    /** Notification API */
    include 'notification.php';

    /** Store API */
    include 'store.php';

    /** Order API */
    include 'order.php';
});
