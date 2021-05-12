<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['member'])->group(function(){
    Route::get("/member-profile", "FrontendMemberController@getProfile")->name("frontend.get.member.profile");
    Route::post("/member-profile", "FrontendMemberController@postProfile")->name("frontend.post.member.profile");
    Route::get("/change-avatar",
               "FrontendMemberController@getChangeAvatar")->name("frontend.post.member.change_avatar");
});
