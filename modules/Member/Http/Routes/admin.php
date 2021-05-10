<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("member")->group(function(){
        Route::get("/", "MemberController@index")->name("get.member.list")->middleware('can:member-view');
    });
});
