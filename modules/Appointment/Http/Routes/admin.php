<?php

use Illuminate\Support\Facades\Route;

Route::prefix("appointment")->group(function(){
    Route::get("/", "AppointmentController@index")->name("get.appointment.list");
});
