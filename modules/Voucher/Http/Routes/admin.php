<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("voucher")->group(function(){
        Route::get("/", "VoucherController@index")->name("get.voucher.list")->middleware('can:voucher');
        Route::get("/get-voucher-list/{id}",
            "VoucherController@getListVoucherByService")->name("get.voucher.get_list_by_service");
        Route::middleware('can:voucher-create')->group(function(){
            Route::get("/create", "VoucherController@getCreate")->name("get.voucher.create");
            Route::post("/create", "VoucherController@postCreate")->name("post.voucher.create");
            Route::get("/create-popup", "VoucherController@getCreatePopUp")->name("get.voucher.create_popup");
            Route::post("/create-popup", "VoucherController@postCreatePopUp")->name("post.voucher.create_popup");
        });
        Route::middleware('can:voucher-update')->group(function(){
            Route::get("/update/{id}", "VoucherController@getUpdate")->name("get.voucher.update");
            Route::post("/update/{id}", "VoucherController@postUpdate")->name("post.voucher.update");
        });
        Route::get("/delete/{id}", "VoucherController@delete")->name("get.voucher.delete")
             ->middleware('can:voucher-delete');
    });
});
