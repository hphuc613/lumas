<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix("admin")->group(function(){
    Route::prefix("appointment")->group(function(){
        Route::middleware('can:appointment')->group(function(){
            Route::get("/", "AppointmentController@index")->name("get.appointment.list");
            Route::get("/overview", "AppointmentController@overview")->name("get.appointment.overview");
            Route::get("/event-list", "AppointmentController@getEventList")->name("get.appointment.event_list");
        });
        Route::middleware('can:appointment-create')->group(function(){
            Route::get("/create", "AppointmentController@getCreate")->name("get.appointment.create");
            Route::post("/create", "AppointmentController@postCreate")->name("post.appointment.create");
            Route::get("/bulk-create", "AppointmentController@getBulkCreate")->name("get.appointment.bulk_create");
            Route::post("/bulk-create", "AppointmentController@postBulkCreate")->name("post.appointment.bulk_create");
        });
        Route::middleware('can:appointment-update')->group(function(){
            Route::get("/update/{id}", "AppointmentController@getUpdate")->name("get.appointment.update");
            Route::post("/update/{id}", "AppointmentController@postUpdate")->name("post.appointment.update");
            Route::post("/update-time/{id}",
                "AppointmentController@postChangeTime")->name("post.appointment.update_time");

            Route::get("/check-in/{id}/{member_id}", "AppointmentController@checkIn")->name("get.appointment.check_in");
            Route::get("/check-out/{id}", "AppointmentController@checkOut")->name("get.appointment.check_out");
        });
        Route::get("/delete/{id}",
            "AppointmentController@delete")->name("get.appointment.delete")->middleware('can:appointment-delete');
        Route::get("/product-list/{id}", "AppointmentController@getProductList")->name('get.appointment.product_list');
    });
});
