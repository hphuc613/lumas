<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("service-type")->group(function(){
        Route::get("/", "ServiceTypeController@index")->name("get.service_type.list");
        Route::get("/create", "ServiceTypeController@getCreate")->name("get.service_type.create");
        Route::post("/create", "ServiceTypeController@postCreate")->name("post.service_type.create");
        Route::get("/update/{id}", "ServiceTypeController@getUpdate")->name("get.service_type.update");
        Route::post("/update/{id}", "ServiceTypeController@postUpdate")->name("post.service_type.update");
        Route::get("/delete/{id}", "ServiceTypeController@delete")->name("get.service_type.delete");
    });
    Route::prefix("service")->group(function(){
        Route::get("/", "ServiceController@index")->name("get.service.list");
    });
});
