<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['member'])->group(function(){
    Route::get("/member-info", "FrontendController@memberInfo")->name("frontend.get.member.info");
});
