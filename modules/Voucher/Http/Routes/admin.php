<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    /** Service Voucher */
    Route::prefix("service-voucher")->group(function(){
        Route::get("/", "ServiceVoucherController@index")->name("get.service_voucher.list")
             ->middleware('can:service-voucher');
        Route::get("/get-voucher-list/{id}", "ServiceVoucherController@getListVoucherByServiceID")
             ->name("get.service_voucher.get_list_by_service");
        Route::middleware('can:service-voucher-create')->group(function(){
            Route::get("/create", "ServiceVoucherController@getCreate")->name("get.service_voucher.create");
            Route::post("/create", "ServiceVoucherController@postCreate")->name("post.service_voucher.create");
            Route::get("/create-popup", "ServiceVoucherController@getCreatePopUp")
                 ->name("get.service_voucher.create_popup");
            Route::post("/create-popup", "ServiceVoucherController@postCreatePopUp")
                 ->name("post.service_voucher.create_popup");
        });
        Route::middleware('can:service-voucher-update')->group(function(){
            Route::get("/update/{id}", "ServiceVoucherController@getUpdate")->name("get.service_voucher.update");
            Route::post("/update/{id}", "ServiceVoucherController@postUpdate")->name("post.service_voucher.update");
        });
        Route::get("/delete/{id}", "ServiceVoucherController@delete")->name("get.service_voucher.delete")
             ->middleware('can:service-voucher-delete');
    });


    /** Course Voucher */
    Route::prefix("course-voucher")->group(function(){
        Route::get("/", "CourseVoucherController@index")->name("get.course_voucher.list")
             ->middleware('can:course-voucher');
        Route::get("/get-course-voucher-list/{id}", "CourseVoucherController@getListVoucherByCourseID")
             ->name("get.course_voucher.get_list_by_course");
        Route::middleware('can:course-voucher-create')->group(function(){
            Route::get("/create", "CourseVoucherController@getCreate")->name("get.course_voucher.create");
            Route::post("/create", "CourseVoucherController@postCreate")->name("post.course_voucher.create");
            Route::get("/create-popup", "CourseVoucherController@getCreatePopUp")
                 ->name("get.course_voucher.create_popup");
            Route::post("/create-popup", "CourseVoucherController@postCreatePopUp")
                 ->name("post.course_voucher.create_popup");
        });
        Route::middleware('can:course-voucher-update')->group(function(){
            Route::get("/update/{id}", "CourseVoucherController@getUpdate")->name("get.course_voucher.update");
            Route::post("/update/{id}", "CourseVoucherController@postUpdate")->name("post.course_voucher.update");
        });
        Route::get("/delete/{id}", "CourseVoucherController@delete")->name("get.course_voucher.delete")
             ->middleware('can:course-voucher-delete');
    });
});
