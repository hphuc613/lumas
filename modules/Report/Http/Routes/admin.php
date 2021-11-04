<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function(){
    Route::get("report-service", "ReportController@service")->name("get.report.service");
    Route::get("report-sale", "ReportController@sale")->name("get.report.sale");
    Route::get("report-service-expendable", "ReportController@serviceExpendable")->name("get.report.service_expendable");
    Route::get("report-treatment", "ReportController@treatment")->name("get.report.treatment");
    Route::get("export-treatment-client/{id}", "ReportController@exportTreatmentClient")
         ->name("get.export.treatment_client");
});
