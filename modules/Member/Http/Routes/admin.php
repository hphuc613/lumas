<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("member")->group(function(){
        Route::get("/", "MemberController@index")->name("get.member.list")->middleware('can:member-view');
        Route::group(['middleware' => 'can:member-update'], function(){
            Route::get('/update/{id}', 'MemberController@getUpdate')->name('get.member.update');
            Route::post('/update/{id}', 'MemberController@postUpdate')->name('post.member.update');
            Route::post('/update-status', 'MemberController@postUpdateStatus')->name('post.member.update_status');
        });
        Route::get('/delete/{id}', 'MemberController@delete')->name('get.member.delete')->middleware('can:member-delete');
    });
});
