<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix("admin")->group(function(){
    Route::prefix("store")->group(function(){
        Route::get("/", "StoreController@index")->name("get.store.list")->middleware('can:store');
        Route::middleware('can:store-create')->group(function(){
            Route::get("/create", "StoreController@getCreate")->name("get.store.create");
            Route::post("/create", "StoreController@postCreate")->name("post.store.create");
        });
        Route::middleware('can:store-update')->group(function(){
            Route::get("/update/{id}", "StoreController@getUpdate")->name("get.store.update");
            Route::post("/update/{id}", "StoreController@postUpdate")->name("post.store.update");
        });
        Route::get("/delete", "StoreController@index")->name("get.store.delete")->middleware('can:store-delete');
    });
});
