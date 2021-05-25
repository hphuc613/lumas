<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("member-type")->group(function(){
        Route::get("/", "MemberTypeController@index")->name("get.member_type.list")->middleware('can:member-type');
        Route::group(['middleware' => 'can:member-type-create'], function(){
            Route::get("/create", "MemberTypeController@getCreate")->name("get.member_type.create");
            Route::post("/create", "MemberTypeController@postCreate")->name("post.member_type.create");
        });
        Route::group(['middleware' => 'can:member-type-update'], function(){
            Route::get("/update/{id}", "MemberTypeController@getUpdate")->name("get.member_type.update");
            Route::post("/update/{id}", "MemberTypeController@postUpdate")->name("post.member_type.update");
        });
        Route::get("/delete/{id}",
            "MemberTypeController@delete")->name("get.member_type.delete")->middleware('can:member-type-delete');
    });
    Route::prefix("member")->group(function(){
        Route::get("/", "MemberController@index")->name("get.member.list")->middleware('can:member');
        Route::group(['middleware' => 'can:member-update'], function(){
            Route::get('/update/{id}', 'MemberController@getUpdate')->name('get.member.update');
            Route::post('/update/{id}', 'MemberController@postUpdate')->name('post.member.update');
            Route::post('/update-status', 'MemberController@postUpdateStatus')->name('post.member.update_status');
        });
        Route::get('/delete/{id}', 'MemberController@delete')
             ->name('get.member.delete')->middleware('can:member-delete');

        Route::get('/add-service/{id}', 'MemberController@getAddService')
             ->name('get.member.add_service')->middleware('can:member-add-service');
    });
});
