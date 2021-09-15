<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("documentation")->group(function(){
        Route::get("/", "DocumentationController@index")->name("get.documentation.list");
        Route::get("create/", "DocumentationController@getCreate")->name("get.documentation.create");
        Route::post("create/", "DocumentationController@postCreate")->name("post.documentation.create");
        Route::get("view/{id}", "DocumentationController@getView")->name("get.documentation.view");
        Route::post("update/{id}", "DocumentationController@postUpdate")->name("post.documentation.update");
        Route::get("delete/{id}", "DocumentationController@delete")->name("get.documentation.delete");
        Route::post("sort", "DocumentationController@sort")->name("post.documentation.sort");
    });

    Route::prefix("documentation-mobile")->group(function(){
        Route::get("/", "DocumentationMobileController@index")->name("get.documentation_mobile.list");
        Route::get("create/", "DocumentationMobileController@getCreate")->name("get.documentation_mobile.create");
        Route::post("create/", "DocumentationMobileController@postCreate")->name("post.documentation_mobile.create");
        Route::get("view/{id}", "DocumentationMobileController@getView")->name("get.documentation_mobile.view");
        Route::post("update/{id}", "DocumentationMobileController@postUpdate")
             ->name("post.documentation_mobile.update");
        Route::get("delete/{id}", "DocumentationMobileController@delete")->name("get.documentation_mobile.delete");
        Route::post("sort", "DocumentationMobileController@sort")->name("post.documentation_mobile.sort");
    });

    Route::prefix("documentation-ct")->group(function(){
        Route::get("/", "DocumentationWebCTController@index")->name("get.documentation_ct.list");
        Route::get("create/", "DocumentationWebCTController@getCreate")->name("get.documentation_ct.create");
        Route::post("create/", "DocumentationWebCTController@postCreate")->name("post.documentation_ct.create");
        Route::get("view/{id}", "DocumentationWebCTController@getView")->name("get.documentation_ct.view");
        Route::post("update/{id}", "DocumentationWebCTController@postUpdate")
             ->name("post.documentation_ct.update");
        Route::get("delete/{id}", "DocumentationWebCTController@delete")->name("get.documentation_ct.delete");
        Route::post("sort", "DocumentationWebCTController@sort")->name("post.documentation_ct.sort");
    });

    Route::prefix("documentation-mobile-ct")->group(function(){
        Route::get("/", "DocumentationMobileCTController@index")->name("get.documentation_mobile_ct.list");
        Route::get("create/", "DocumentationMobileCTController@getCreate")->name("get.documentation_mobile_ct.create");
        Route::post("create/", "DocumentationMobileCTController@postCreate")
             ->name("post.documentation_mobile_ct.create");
        Route::get("view/{id}", "DocumentationMobileCTController@getView")->name("get.documentation_mobile_ct.view");
        Route::post("update/{id}", "DocumentationMobileCTController@postUpdate")
             ->name("post.documentation_mobile_ct.update");
        Route::get("delete/{id}", "DocumentationMobileCTController@delete")->name("get.documentation_mobile_ct.delete");
        Route::post("sort", "DocumentationMobileCTController@sort")->name("post.documentation_mobile_ct.sort");
    });
});
