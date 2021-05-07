<?php
use Illuminate\Support\Facades\Route;

Route::prefix("member")->group(function (){
    Route::get("/", "MemberController@index")->name("get.member.list");
});
