<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function(){
        Route::get("report-service", "ReportController@service")->name("get.report.service");
        Route::get("report-treatment", "ReportController@treatment")->name("get.report.treatment");
});
