<?php
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'IndexController@getIndex')->name('admin');
    Route::get('404', 'IndexController@get404')->name('admin.dashboard.404');
    Route::get('dashboard', 'IndexController@getIndex')->name('admin.dashboard.index');
    Route::get('modules', 'IndexController@getModules')->name('admin.modules.index');
    Route::get('modules/{slug}', 'IndexController@getModuleDetail')->name('admin.modules.detail');
    Route::get('report/article', 'ReportController@getReportArticle')->name('report.article');
    Route::get('report/media', 'ReportController@getReportMedia')->name('report.media');
    Route::get('report/logs', 'ReportController@getReportLogs')->name('report.logs');
    Route::get('report/api', 'ReportController@getDashboardApi')->name('report.logs.api');

    /* API */
    Route::post('report/api/data-chart-month-year', 'ReportController@ApiDataChartMonthYear')->name('report.logs.api.data-chart-month-year');
    Route::post('report/api/data-chart-hour', 'ReportController@ApiDataChartHour')->name('report.logs.api.data-chart-hour');
    Route::post('report/api/data-chart-issues', 'ReportController@ApiDataChartIssues')->name('report.logs.api.data-chart-issues');
    Route::post('report/api/data-chart-target', 'ReportController@ApiDataChartTarget')->name('report.logs.api.data-chart-target');
    Route::post('report/api/data-chart-setting', 'ReportController@ApiDataChartSetting')->name('report.logs.api.data-chart-setting');
    Route::post('report/api/data-keyword-search', 'ReportController@ApiDataKeywordSearch')->name('report.logs.api.data-keyword-search');           
    Route::post('report/api/data-most-viewed-statistic', 'ReportController@ApiDataMostViewedStatistics')->name('report.logs.api.data-most-viewed-statistic'); 
    Route::post('report/api/data-statistics-api-by-organization', 'ReportController@ApiDataStatisticsApiByOrganization')->name('report.logs.api.data-statistics-api-by-organization'); 


});
