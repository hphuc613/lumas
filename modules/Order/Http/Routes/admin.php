<?php

use Illuminate\Support\Facades\Route;

Route::prefix("order")->group(function(){
    Route::get("/", "OrderController@index")->name("get.order.list");
});
