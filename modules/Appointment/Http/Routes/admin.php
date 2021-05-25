<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix("admin")->group(function(){
    Route::prefix("appointment")->group(function(){
        Route::get("/", "AppointmentController@index")->name("get.appointment.list")->middleware('can:appointment');
        Route::middleware('can:appointment-create')->group(function(){
            Route::get("/create", "AppointmentController@getCreate")->name("get.appointment.create");
            Route::post("/create", "AppointmentController@postCreate")->name("post.appointment.create");
        });
        Route::middleware('can:appointment-update')->group(function(){
            Route::get("/update/{id}", "AppointmentController@getUpdate")->name("get.appointment.update");
            Route::post("/update/{id}", "AppointmentController@postUpdate")->name("post.appointment.update");
            Route::post("/update-time/{id}",
                        "AppointmentController@postChangeTime")->name("post.appointment.update_time");
        });
        Route::get("/delete/{id}",
                   "AppointmentController@delete")->name("get.appointment.delete")->middleware('can:appointment-delete');
    });
});
