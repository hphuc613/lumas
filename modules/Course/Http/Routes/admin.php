<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("course-category")->group(function(){
        Route::get("/", "CourseCategoryController@index")
             ->name("get.course_category.list")
             ->middleware('can:course-category');

        Route::middleware('can:course-category-create')->group(function(){
            Route::get("/create", "CourseCategoryController@getCreate")->name("get.course_category.create");
            Route::post("/create", "CourseCategoryController@postCreate")->name("post.course_category.create");
        });

        Route::middleware('can:course-category-update')->group(function(){
            Route::get("/update/{id}", "CourseCategoryController@getUpdate")->name("get.course_category.update");
            Route::post("/update/{id}", "CourseCategoryController@postUpdate")->name("post.course_category.update");
        });

        Route::get("/delete/{id}", "CourseCategoryController@delete")
             ->name("get.course_category.delete")
             ->middleware('can:course-category-delete');
    });

    Route::prefix("course")->group(function(){
        Route::get("/", "CourseController@index")->name("get.course.list")->middleware('can:course');

        Route::middleware('can:course-create')->group(function(){
            Route::get("/create", "CourseController@getCreate")->name("get.course.create");
            Route::post("/create", "CourseController@postCreate")->name("post.course.create");
        });
        Route::middleware('can:course-update')->group(function(){
            Route::get("/update/{id}", "CourseController@getUpdate")->name("get.course.update");
            Route::post("/update/{id}", "CourseController@postUpdate")->name("post.course.update");
        });

        Route::get("/delete/{id}", "CourseController@delete")->name("get.course.delete")
             ->middleware('can:course-delete');
    });
});
