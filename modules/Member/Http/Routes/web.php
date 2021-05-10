<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['member'])->group(function(){
    Route::get("/member-profile", "FrontendMemberController@profile")->name("frontend.get.member.profile");
    Route::post("/member-profile", "FrontendMemberController@profile")->name("frontend.post.member.profile");
});
