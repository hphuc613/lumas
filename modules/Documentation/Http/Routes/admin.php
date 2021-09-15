<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("documentation")->group(function(){
        Route::get("/", "DocumentationController@index")->name("get.documentation.list");
        Route::get("create/", "DocumentationController@getCreate")->name("get.documentation.create");
        Route::post("create/", "DocumentationController@postCreate")->name("post.documentation.create");
        Route::get("view/{id}", "DocumentationController@getView")->name("get.documentation.view");
        Route::post("update/{id}", "DocumentationController@postUpdate")->name("post.documentation.update");
        Route::get("delete/{id}", "DocumentationController@delete")->name("get.documentation.delete");
        Route::post("sort", "DocumentationController@sort")->name("post.documentation.sort");
    });

    Route::prefix("documentation-mobile")->group(function(){
        Route::get("/", "DocumentationMobileController@index")->name("get.documentation_mobile.list");
        Route::get("create/", "DocumentationMobileController@getCreate")->name("get.documentation_mobile.create");
        Route::post("create/", "DocumentationMobileController@postCreate")->name("post.documentation_mobile.create");
        Route::get("view/{id}", "DocumentationMobileController@getView")->name("get.documentation_mobile.view");
        Route::post("update/{id}", "DocumentationMobileController@postUpdate")
             ->name("post.documentation_mobile.update");
        Route::get("delete/{id}", "DocumentationMobileController@delete")->name("get.documentation_mobile.delete");
        Route::post("sort", "DocumentationMobileController@sort")->name("post.documentation_mobile.sort");
    });

    Route::prefix("documentation-ct")->group(function(){
        Route::get("/", "DocumentationWebCTController@index")->name("get.documentation_ct.list");
        Route::get("create/", "DocumentationWebCTController@getCreate")->name("get.documentation_ct.create");
        Route::post("create/", "DocumentationWebCTController@postCreate")->name("post.documentation_ct.create");
        Route::get("view/{id}", "DocumentationWebCTController@getView")->name("get.documentation_ct.view");
        Route::post("update/{id}", "DocumentationWebCTController@postUpdate")
             ->name("post.documentation_ct.update");
        Route::get("delete/{id}", "DocumentationWebCTController@delete")->name("get.documentation_ct.delete");
        Route::post("sort", "DocumentationWebCTController@sort")->name("post.documentation_ct.sort");
    });

    Route::prefix("documentation-mobile-ct")->group(function(){
        Route::get("/", "DocumentationMobileCTController@index")->name("get.documentation_mobile_ct.list");
        Route::get("create/", "DocumentationMobileCTController@getCreate")->name("get.documentation_mobile_ct.create");
        Route::post("create/", "DocumentationMobileCTController@postCreate")
             ->name("post.documentation_mobile_ct.create");
        Route::get("view/{id}", "DocumentationMobileCTController@getView")->name("get.documentation_mobile_ct.view");
        Route::post("update/{id}", "DocumentationMobileCTController@postUpdate")
             ->name("post.documentation_mobile_ct.update");
        Route::get("delete/{id}", "DocumentationMobileCTController@delete")->name("get.documentation_mobile_ct.delete");
        Route::post("sort", "DocumentationMobileCTController@sort")->name("post.documentation_mobile_ct.sort");
    });

    Route::prefix("guide-client")->group(function(){
        Route::get("/", "GuideClientController@index")->name("get.guide_client.list");
        Route::get("create/", "GuideClientController@getCreate")->name("get.guide_client.create");
        Route::post("create/", "GuideClientController@postCreate")
             ->name("post.guide_client.create");
        Route::get("view/{id}", "GuideClientController@getView")->name("get.guide_client.view");
        Route::post("update/{id}", "GuideClientController@postUpdate")
             ->name("post.guide_client.update");
        Route::get("delete/{id}", "GuideClientController@delete")->name("get.guide_client.delete");
        Route::post("sort", "GuideClientController@sort")->name("post.guide_client.sort");
    });

    Route::prefix("guide-client-tc")->group(function(){
        Route::get("/", "GuideClientTCController@index")->name("get.guide_client_tc.list");
        Route::get("create/", "GuideClientTCController@getCreate")->name("get.guide_client_tc.create");
        Route::post("create/", "GuideClientTCController@postCreate")
             ->name("post.guide_client_tc.create");
        Route::get("view/{id}", "GuideClientTCController@getView")->name("get.guide_client_tc.view");
        Route::post("update/{id}", "GuideClientTCController@postUpdate")
             ->name("post.guide_client_tc.update");
        Route::get("delete/{id}", "GuideClientTCController@delete")->name("get.guide_client_tc.delete");
        Route::post("sort", "GuideClientTCController@sort")->name("post.guide_client_tc.sort");
    });

    Route::prefix("guide-staff")->group(function(){
        Route::get("/", "GuideStaffController@index")->name("get.guide_staff.list");
        Route::get("create/", "GuideStaffController@getCreate")->name("get.guide_staff.create");
        Route::post("create/", "GuideStaffController@postCreate")
             ->name("post.guide_staff.create");
        Route::get("view/{id}", "GuideStaffController@getView")->name("get.guide_staff.view");
        Route::post("update/{id}", "GuideStaffController@postUpdate")
             ->name("post.guide_staff.update");
        Route::get("delete/{id}", "GuideStaffController@delete")->name("get.guide_staff.delete");
        Route::post("sort", "GuideStaffController@sort")->name("post.guide_staff.sort");
    });

    Route::prefix("guide-staff-tc")->group(function(){
        Route::get("/", "GuideStaffTCController@index")->name("get.guide_staff_tc.list");
        Route::get("create/", "GuideStaffTCController@getCreate")->name("get.guide_staff_tc.create");
        Route::post("create/", "GuideStaffTCController@postCreate")
             ->name("post.guide_staff_tc.create");
        Route::get("view/{id}", "GuideStaffTCController@getView")->name("get.guide_staff_tc.view");
        Route::post("update/{id}", "GuideStaffTCController@postUpdate")
             ->name("post.guide_staff_tc.update");
        Route::get("delete/{id}", "GuideStaffTCController@delete")->name("get.guide_staff_tc.delete");
        Route::post("sort", "GuideStaffTCController@sort")->name("post.guide_staff_tc.sort");
    });
});
