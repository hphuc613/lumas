<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function(){

    /** User API */
    include 'user.php';

    /** Member API */
    include 'member.php';
});