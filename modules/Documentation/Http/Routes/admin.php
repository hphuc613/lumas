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
});
