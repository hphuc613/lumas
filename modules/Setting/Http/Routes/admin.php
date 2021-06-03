<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::prefix("setting")->group(function(){
        Route::get("/", "SettingController@index")->name("get.setting.list");

        Route::get("email/", "SettingController@emailConfig")->name("get.setting.emailConfig");
        Route::post("email/", "SettingController@emailConfig")->name("post.setting.emailConfig");
        Route::get("test-send-mail/", "SettingController@testSendMail")->name("get.setting.testSendMail");

        Route::get("website/", "SettingController@websiteConfig")->name("get.setting.websiteConfig");
        Route::post("website/", "SettingController@websiteConfig")->name("post.setting.websiteConfig");
        Route::get("language/", "SettingController@langManagement")->name("get.setting.langManagement");
        Route::post("language/", "SettingController@langManagement")->name("post.setting.langManagement");

        Route::get("appointment/", "SettingController@appointmentConfig")->name("get.setting.appointmentConfig");
        Route::post("appointment/", "SettingController@appointmentConfig")->name("post.setting.appointmentConfig");
    });
});
