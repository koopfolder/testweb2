<?php

/*Backend*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('api/list-media', 'MediaController@getIndex')->name('admin.api.list-media.index');
    Route::get('api/list-media/edit/{id}', 'MediaController@getEdit')->name('admin.api.list-media.edit');
    Route::post('api/list-media/edit/{id}', 'MediaController@postEdit')->name('admin.api.list-media.edit');
    
    Route::get('api/list-category', 'CategoryController@getIndex')->name('admin.api.list-category.index');
    Route::get('api/list-category/edit/{id}', 'CategoryController@getEdit')->name('admin.api.list-category.edit');
    Route::post('api/list-category/edit/{id}', 'CategoryController@postEdit')->name('admin.api.list-category.edit');

    Route::get('api/list-issue', 'IssueController@getIndex')->name('admin.api.list-issue.index');
    Route::get('api/list-issue/edit/{id}', 'IssueController@getEdit')->name('admin.api.list-issue.edit');
    Route::post('api/list-issue/edit/{id}', 'IssueController@postEdit')->name('admin.api.list-issue.edit');

    Route::get('api/list-target', 'TargetController@getIndex')->name('admin.api.list-target.index');
    Route::get('api/list-target/edit/{id}', 'TargetController@getEdit')->name('admin.api.list-target.edit');
    Route::post('api/list-target/edit/{id}', 'TargetController@postEdit')->name('admin.api.list-target.edit');

    Route::get('api/list-setting', 'SettingController@getIndex')->name('admin.api.list-setting.index');
    Route::get('api/list-setting/edit/{id}', 'SettingController@getEdit')->name('admin.api.list-setting.edit');
    Route::post('api/list-setting/edit/{id}', 'SettingController@postEdit')->name('admin.api.list-setting.edit');

    Route::get('api/list-area', 'AreaController@getIndex')->name('admin.api.list-area.index');
    Route::get('api/list-area/edit/{id}', 'AreaController@getEdit')->name('admin.api.list-area.edit');
    Route::post('api/list-area/edit/{id}', 'AreaController@postEdit')->name('admin.api.list-area.edit');

    Route::get('api/list-province', 'ProvinceController@getIndex')->name('admin.api.list-province.index');
    Route::get('api/list-province/edit/{id}', 'ProvinceController@getEdit')->name('admin.api.list-province.edit');
    Route::post('api/list-province/edit/{id}', 'ProvinceController@postEdit')->name('admin.api.list-province.edit');


    Route::get('request-media/detail', 'RequestMediaDetailController@getIndex')->name('admin.request-media-detail.index');
    Route::get('request-media/detail/create', 'RequestMediaDetailController@getCreate')->name('admin.request-media-detail.create');
    Route::post('request-media/detail/create', 'RequestMediaDetailController@postCreate')->name('admin.request-media-detail.create');
    Route::get('request-media/detail/edit/{id}', 'RequestMediaDetailController@getEdit')->name('admin.request-media-detail.edit');
    Route::post('request-media/detail/edit/{id}', 'RequestMediaDetailController@postEdit')->name('admin.request-media-detail.edit');
    Route::get('request-media/detail/delete/{id}', 'RequestMediaDetailController@getDelete')->name('admin.request-media-detail.delete');
    Route::post('request-media/detail/deleteAll', 'RequestMediaDetailController@postDeleteAll')->name('admin.request-media-detail.deleteAll');


    Route::get('request-media/email', 'RequestMediaEmailController@getIndex')->name('admin.request-media-email.index');
    Route::get('request-media/email/create', 'RequestMediaEmailController@getCreate')->name('admin.request-media-email.create');
    Route::post('request-media/email/create', 'RequestMediaEmailController@postCreate')->name('admin.request-media-email.create');
    Route::get('request-media/email/edit/{id}', 'RequestMediaEmailController@getEdit')->name('admin.request-media-email.edit');
    Route::post('request-media/email/edit/{id}', 'RequestMediaEmailController@postEdit')->name('admin.request-media-email.edit');
    Route::get('request-media/email/delete/{id}', 'RequestMediaEmailController@getDelete')->name('admin.request-media-email.delete');
    Route::post('request-media/email/deleteAll', 'RequestMediaEmailController@postDeleteAll')->name('admin.request-media-email.deleteAll');

    Route::get('request-media','RequestMediaController@getIndex')->name('admin.request-media.index');

});

Route::post('api/generate-key','IndexController@postGenerateKey')->name('api.generate-key.index');
Route::post('api/list-media','IndexController@postListMedia')->name('api.list-media.index');
Route::post('api/get-media','IndexController@postMedia')->name('api.media.index');
Route::post('api/list-category','IndexController@postListCategory')->name('api.list-category.index');
Route::post('api/list-issue','IndexController@postListIssue')->name('api.list-issue.index');
Route::post('api/list-target','IndexController@postListTarget')->name('api.list-target.index');
Route::post('api/list-setting','IndexController@postListSetting')->name('api.list-setting.index');
Route::post('api/list-area','IndexController@postListArea')->name('api.list-area.index');
Route::post('api/list-province','IndexController@postListProvince')->name('api.list-province.index');
Route::post('api/task-department','IndexController@postTaskDepartment')->name('api.task.department.index');
Route::post('api/task-media','IndexController@postTaskMedia')->name('api.task.media.index');
Route::post('api/task-media-attribute','IndexController@postTaskMediaAttribute')->name('api.task.media.attribute.index');
Route::post('api/get-media-attribute','IndexController@postMediaAttribute')->name('api.media.attribute.index');

//Route::get('api/test','IndexController@getTest')->name('api.test.index');
//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::group([], function(){
    
    Route::get('knowledge-set/{id}','FrontendController@getDetailMedia')->name('knowledges-detail');
    Route::get('media-campaign/{id}','FrontendController@getDetailMedia')->name('media-campaign-detail');
    Route::get('media/list','FrontendController@getListMedia')->name('media-list');
    Route::get('media/{id}','FrontendController@getDetailMedia')->name('media-detail');
    Route::get('article/{slug}','FrontendController@getDetailArticle')->name('article-detail');
    Route::get('thaihealthwatch/{slug}','FrontendController@getDetailArticle')->name('thaihealthwatch-detail');
    Route::get('พื้นที่เรียนรู้สร้างประสบการณ์ตรง/{slug}','FrontendController@getDetailArticle')->name('learning-area-creates-direct-experience-detail');
    Route::get('media-include-statistics/{id}','FrontendController@getDetailMediaCaseIncludeStatistics')->name('media-detail-case-include-statistics');
    Route::get('article-include-statistics/{slug}','FrontendController@getDetailArticleCaseIncludeStatistics')->name('article-detail-case-include-statistics');
    Route::get('api/autocomplete-ajax','FrontendController@ajaxData')->name('ajaxData'); 
    Route::post('request-media/front/create','RequestMediaController@postFrontCreate')->name('request-media-front.create');
    Route::post('document-download/front/update','FrontendController@postDownload')->name('document-download.update');
    Route::post('media-download/front/update','FrontendController@postMediaDownload')->name('media-download.update');

});
