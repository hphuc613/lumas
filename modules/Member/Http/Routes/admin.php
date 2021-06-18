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
        Route::get("/appointment/{id}", "MemberController@getAppointment")->name("get.member.appointment")
             ->middleware('can:member');
        Route::group(['middleware' => 'can:member-create'], function(){
            Route::get('/create', 'MemberController@getCreate')->name('get.member.create');
            Route::post('/create', 'MemberController@postCreate')->name('post.member.create');
            Route::get('/import-member', 'MemberController@getImport')->name('get.member.import');
            Route::post('/import-member', 'MemberController@postImport')->name('post.member.import');
        });
        Route::group(['middleware' => 'can:member-update'], function(){
            Route::get('/update/{id}', 'MemberController@getUpdate')->name('get.member.update');
            Route::post('/update/{id}', 'MemberController@postUpdate')->name('post.member.update');
            Route::post('/update-status', 'MemberController@postUpdateStatus')->name('post.member.update_status');
        });
        Route::get('/delete/{id}', 'MemberController@delete')
             ->name('get.member.delete')->middleware('can:member-delete');


        /** ADD Service For Client */
        Route::middleware('can:member-add-service')->group(function(){
            Route::prefix("service")->group(function(){
                Route::get('/add/{id}', 'MemberServiceController@getAdd')->name('get.member_service.add');
                Route::get('/view/{id}', 'MemberServiceController@getView')->name('get.member_service.view');
                Route::get('/delete/{id}', 'MemberServiceController@delete')->name('get.member_service.delete');

                /** E sign */
                Route::get('/e-sign/{id}', 'MemberServiceController@eSign')->name('get.member_service.e_sign');
                Route::post('/e-sign/{id}', 'MemberServiceController@eSign')->name('get.member_service.e_sign');

                /** Progress Handle */
                Route::get('/into-progress/{id}', 'MemberServiceController@intoProgress')
                     ->name('get.member_service.into_progress');
                Route::get('/out-progress/{id}', 'MemberServiceController@outProgress')
                     ->name('get.member_service.out_progress');
            });
        });

        /** ADD Course For Client */
        Route::middleware('can:member-add-course')->group(function(){
            Route::prefix("course")->group(function(){
                Route::get('/add/{id}', 'MemberCourseController@getAdd')->name('get.member_course.add');
                Route::post('/add/{id}', 'MemberCourseController@postAdd')->name('post.member_course.add');
                Route::get('/edit/{id}', 'MemberCourseController@getEdit')->name('get.member_course.edit');
                Route::post('/edit/{id}', 'MemberCourseController@postEdit')->name('post.member_course.edit');
                Route::get('/delete/{id}', 'MemberCourseController@delete')->name('get.member_course.delete');

                /** E sign */
                Route::get('/e-sign/{id}', 'MemberCourseController@eSign')->name('get.member_course.e_sign');
                Route::post('/e-sign/{id}', 'MemberCourseController@eSign')->name('get.member_course.e_sign');

                /** Progress Handle */
                Route::get('/into-progress/{id}', 'MemberCourseController@intoProgress')
                     ->name('get.member_course.into_progress');
                Route::get('/out-progress/{id}', 'MemberCourseController@outProgress')
                     ->name('get.member_course.out_progress');
            });
        });
    });
});
