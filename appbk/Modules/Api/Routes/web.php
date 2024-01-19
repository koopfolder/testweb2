<?php

/*Backend*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('api/list-media', 'MediaController@getIndex')->name('admin.api.list-media.index');
    Route::get('api/list-media/edit/{id}', 'MediaController@getEdit')->name('admin.api.list-media.edit');
    Route::post('api/list-media/edit/{id}', 'MediaController@postEdit')->name('admin.api.list-media.edit');
    Route::post('api/list-media/update-status', 'MediaController@postUpdateStatus')->name('admin.api.list-media.update-status');
    Route::post('api/list-media/transfer', 'MediaController@postTransfer')->name('admin.api.list-media.transfer');

    Route::get('api/list-media/import', 'MediaController@getImport')->name('admin.api.list-media.import');
    Route::get('api/list-media/export-excel-api-webview', 'MediaController@getExcelReport')->name('admin.api.list-media.export-excel-api-webview');

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


    Route::get('sex', 'SexController@getIndex')->name('admin.sex.index');
    Route::get('sex/create', 'SexController@getCreate')->name('admin.sex.create');
    Route::post('sex/create', 'SexController@postCreate')->name('admin.sex.create');
    Route::get('sex/edit/{id}', 'SexController@getEdit')->name('admin.sex.edit');
    Route::post('sex/edit/{id}', 'SexController@postEdit')->name('admin.sex.edit');
    Route::get('sex/delete/{id}', 'SexController@getDelete')->name('admin.sex.delete');
    Route::post('sex/deleteAll', 'SexController@postDeleteAll')->name('admin.sex.deleteAll');

    Route::get('age', 'AgeController@getIndex')->name('admin.age.index');
    Route::get('age/create', 'AgeController@getCreate')->name('admin.age.create');
    Route::post('age/create', 'AgeController@postCreate')->name('admin.age.create');
    Route::get('age/edit/{id}', 'AgeController@getEdit')->name('admin.age.edit');
    Route::post('age/edit/{id}', 'AgeController@postEdit')->name('admin.age.edit');
    Route::get('age/delete/{id}', 'AgeController@getDelete')->name('admin.age.delete');
    Route::post('age/deleteAll', 'AgeController@postDeleteAll')->name('admin.age.deleteAll');


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

    Route::get('request-media', 'RequestMediaController@getIndex')->name('admin.request-media.index');
    //movetotrash
    Route::post('/api/list-media/movetotrash', 'MediaController@movetotrash')->name('movetotrash');
    Route::post('/api/list-media/recycletrash', 'MediaController@recycletrash')->name('recycletrash');
    Route::post('/api/list-media/deletemediafile', 'MediaController@deletemediafile')->name('deletemediafile');
    Route::post('/api/list-media/delecttrash', 'MediaController@delecttrash')->name('delecttrash');
});

// test ENV
Route::get('test/env', 'IndexController@GET_ENV')->name('GETENV');

// api for download media
Route::get('download/{base64id}', 'IndexController@getMediaDownload')->name('api.donwload-media');

// api for get data 
Route::post('api/get-json-data', 'IndexController@getJsonData')->name('api.get-json-data');

Route::post('api/generate-key', 'IndexController@postGenerateKey')->name('api.generate-key.index');
Route::post('api/list-media', 'IndexController@postListMedia')->name('api.list-media.index');
Route::post('api/get-media', 'IndexController@postMedia')->name('api.media.index');
Route::post('api/media-update', 'IndexController@postMediaUpdate')->name('api.media-update.index');
Route::post('api/list-category', 'IndexController@postListCategory')->name('api.list-category.index');
Route::post('api/list-issue', 'IndexController@postListIssue')->name('api.list-issue.index');
Route::post('api/list-target', 'IndexController@postListTarget')->name('api.list-target.index');
Route::post('api/list-setting', 'IndexController@postListSetting')->name('api.list-setting.index');
Route::post('api/list-area', 'IndexController@postListArea')->name('api.list-area.index');
Route::post('api/list-province', 'IndexController@postListProvince')->name('api.list-province.index');
Route::post('api/task-department', 'IndexController@postTaskDepartment')->name('api.task.department.index');
Route::post('api/task-media', 'IndexController@postTaskMedia')->name('api.task.media.index');
Route::post('api/task-media-update', 'IndexController@postTaskMediaUpdate')->name('api.task.media-update.index');
Route::post('api/task-media-attribute', 'IndexController@postTaskMediaAttribute')->name('api.task.media.attribute.index');
Route::post('api/get-media-attribute', 'IndexController@postMediaAttribute')->name('api.media.attribute.index');

Route::post('api/ncds-1', 'FrontendController@postNcds1')->name('api.ncds.ncds1');
Route::post('api/ncds-2', 'FrontendController@postNcds2')->name('api.ncds.ncds2');
Route::post('api/ncds-3', 'FrontendController@postNcds3')->name('api.ncds.ncds3');
Route::post('api/ncds-4', 'FrontendController@postNcds4')->name('api.ncds.ncds4');
Route::post('api/ncds-5', 'FrontendController@postNcds5')->name('api.ncds.ncds5');
Route::post('api/ncds-6', 'FrontendController@postNcds6')->name('api.ncds.ncds6');
Route::post('api/ncds-7', 'FrontendController@postNcds7')->name('api.ncds.ncds7');
Route::post('api/notable-books', 'FrontendController@postNotableBooks')->name('api.notable-books');
Route::post('api/ncds-2-list', 'FrontendController@postNcds2list')->name('api.ncds.ncds2-list');
Route::post('api/ncds-2-list-readmore', 'FrontendController@postNcds2ListLoadMore')->name('api.ncds.ncds2-list-readmore');
//Route::get('api/test','IndexController@getTest')->name('api.test.index');
//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::get('api/import', 'IndexController@getImport')->name('api.import.index');
Route::get('api/import2', 'IndexController@getImport2')->name('api.import2.index');
Route::get('api/import3', 'IndexController@getImport3')->name('api.import3.index');
Route::get('api/importMobile', 'IndexController@getImportMobile')->name('api.importmobile.index');
Route::get('api/import-recommend', 'IndexController@getImportRecommend')->name('api.import-recommend.index');
Route::get('api/import-202', 'IndexController@getImport202')->name('api.import202.index');
Route::get('api/getmedia-202', 'IndexController@getMedia202')->name('api.media202.index');
Route::get('api/updatemedia-202', 'IndexController@getUpdateMedia202')->name('api.updatemedia202.index');
Route::get('api/update-tags', 'IndexController@getUpdateTags')->name('api.update-tags.index');
Route::get('api/update-images', 'IndexController@getUpdateImages')->name('api.update-images.index');
Route::get('api/update-ncds', 'IndexController@getUpdateNcds')->name('api.update-ncds.index');
Route::get('api/update-ncds2', 'IndexController@getUpdateNcds2')->name('api.update-ncds2.index');
Route::get('api/update-ncds3', 'IndexController@getUpdateNcds3')->name('api.update-ncds3.index');
Route::get('api/update-ncds4', 'IndexController@getUpdateNcds4')->name('api.update-ncds4.index');
Route::get('api/update7', 'IndexController@getUpdate7')->name('api.update7.index');
Route::get('api/update-api', 'IndexController@getUpdateApi')->name('api.update-api.index');
Route::post('api/thaihealth-watch-mainbanner', 'FrontendController@postThWatchMainBanner')->name('api.thaihealth-watch.mainbanner');
Route::post('api/thaihealth-watch-footerbanner', 'FrontendController@postThWatchFooterBanner')->name('api.thaihealth-watch.footerbanner');
Route::post('api/thaihealth-watch-single-page', 'FrontendController@postThWatchSinglePage')->name('api.thaihealth-watch.single_page');
Route::post('api/thaihealth-watch-main-video', 'FrontendController@postThWatchMainVideo')->name('api.thaihealth-watch.main_video');
Route::post('api/thaihealth-watch-health-trends', 'FrontendController@postThWatchHealthTrends')->name('api.thaihealth-watch.health_trends');
Route::post('api/thaihealth-watch-panel-discussion', 'FrontendController@postPanelDiscussion')->name('api.thaihealth-watch.panel_discussion');
Route::post('api/thaihealth-watch-points-to-watch', 'FrontendController@postPointsToWatch')->name('api.thaihealth-watch.points_to_watch');
Route::post('api/thaihealth-watch-panel-discussion-list', 'FrontendController@postPanelDiscussionlist')->name('api.thaihealth-watch.panel-discussion-list');
Route::post('api/thaihealth-watch-panel-discussion-list-readmore', 'FrontendController@postPanelDiscussionLoadMore')->name('api.thaihealth-watch.panel-discussion-list-readmore');
Route::post('api/thaihealth-watch-interesting-point-list', 'FrontendController@postInterestingPointlist')->name('api.thaihealth-watch.interesting-point-list');
Route::post('api/thaihealth-watch-interesting-point-list-readmore', 'FrontendController@postInterestingPointLoadMore')->name('api.thaihealth-watch.interesting-point-list-readmore');
Route::post('api/thaihealth-watch-health-trends-list', 'FrontendController@postHealthTrendslist')->name('api.thaihealth-watch.health-trends-list');
Route::post('api/thaihealth-watch-health-trends-list-readmore', 'FrontendController@postHealthTrendsLoadMore')->name('api.thaihealth-watch.health-trends-list-readmore');
Route::post('api/thaihealth-watch-form-generator', 'FrontendController@postFormGenerator')->name('api.thaihealth-watch.form-generator');
Route::get('job/senduserdatato_connectX', 'MediaController@job_user_connect')->name('job_user_connect');
Route::get('clear/test', function() {
    Cache::forget('data_media_knowledges');
    Cache::forget('data_media_campaign');
    Cache::forget('data_interesting_issues');
    Cache::forget('data_articles_research');
    Cache::forget('data_include_statistics');
    return 'Hello World';
});

Route::group([], function () {

    Route::get('knowledge-set/{id}', 'FrontendController@getDetailMedia')->name('knowledges-detail');
    Route::get('media-campaign/{id}', 'FrontendController@getDetailMedia')->name('media-campaign-detail');
    Route::get('media/list', 'FrontendController@getListMedia')->name('media-list');
    Route::get('media/list-webview', 'FrontendController@getListMediaWebView')->name('media-list-webview');
    Route::get('knowledges/list', 'FrontendController@getListKnowledgesMedia')->name('knowledges-list');
    Route::get('media-campaigns/list', 'FrontendController@getListCampaignsMedia')->name('media-campaign-list');
    Route::get('media/{id}', 'FrontendController@getDetailMedia')->name('media-detail');
    Route::get('media2/{id}', 'FrontendController@getDetailMedia2')->name('media2-detail');
    Route::get('media-webview/{id}', 'FrontendController@getDetailMediaWebView')->name('media-detail-webview');
    Route::get('article/{slug}', 'FrontendController@getDetailArticle')->name('article-detail');
    Route::get('article-ncds1/{slug}', 'FrontendController@getDetailArticleNcds1')->name('article-ncds1-detail');
    Route::get('article-ncds6/{slug}', 'FrontendController@getDetailArticleNcds6')->name('article-ncds6-detail');
    Route::get('article-ncds2/{slug}', 'FrontendController@getDetailArticleNcds2')->name('article-ncds2-detail');
    Route::get('article-webview/{slug}', 'FrontendController@getDetailArticleWebView')->name('article-detail-webview');
    Route::get('health-literacy/{slug}', 'FrontendController@getDetailHealthliteracy')->name('health-literacy-detail');
    Route::get('thaihealthwatch/{slug}', 'FrontendController@getDetailArticleThaihealthWatch')->name('thaihealthwatch-detail');
    Route::get('thaihealthwatch-album/{slug}', 'FrontendController@getDetailArticleThaihealthWatch2')->name('thaihealthwatch-detail2');
    Route::get('พื้นที่เรียนรู้สร้างประสบการณ์ตรง/{slug}', 'FrontendController@getDetailArticle')->name('learning-area-creates-direct-experience-detail');
    Route::get('media-include-statistics/{id}', 'FrontendController@getDetailMediaCaseIncludeStatistics')->name('media-detail-case-include-statistics');
    Route::get('article-include-statistics/{slug}', 'FrontendController@getDetailArticleCaseIncludeStatistics')->name('article-detail-case-include-statistics');
    Route::get('api/autocomplete-ajax', 'FrontendController@ajaxData')->name('ajaxData');
    Route::get('other-interesting-tools/list', 'FrontendController@getListNcds6')->name('article-other-interesting-tools');
    Route::get('health-knowledge-skills/list', 'FrontendController@getListNcds4')->name('article-health-knowledge-skills');
    Route::get('know-ncds/list', 'FrontendController@getListNcds1')->name('article-know-ncds');
    Route::get('update-the-status-here-ncds/list', 'FrontendController@getListNcds2')->name('article-update-the-status-here-ncds');
    Route::get('thaihealth-watch/register', 'FrontendController@getThaihealthWatchRegister')->name('thaihealth-watch.register');
    Route::get('thaihealth-watch-health-trends/list', 'FrontendController@getThaihealthWatchHealthTrends')->name('thaihealth-watch.health-trends-list');
    Route::get('thaihealth-watch-panel-discussion/list', 'FrontendController@getThaihealthWatchPanelDiscussion')->name('thaihealth-watch.panel-discussion-list');
    Route::get('thaihealth-watch-interesting-point/list', 'FrontendController@getThaihealthWatchPointsToWatch')->name('thaihealth-watch.points-to-watch-list');
    Route::get('thaihealth-watch/form/{slug}', 'FrontendController@getThaihealthWatchForm')->name('thaihealth-watch.front.form');
    Route::get('thaihealth-watch-interesting-point-article/list', 'FrontendController@getThaihealthWatchPointsToWatchArticle')->name('thaihealth-watch.points-to-watch-article-list');
    Route::get('thaihealth-watch-interesting-point-video/list', 'FrontendController@getThaihealthWatchPointsToWatchVideo')->name('thaihealth-watch.points-to-watch-video-list');
    Route::get('thaihealth-watch-interesting-point-gallery/list', 'FrontendController@getThaihealthWatchPointsToWatchGallery')->name('thaihealth-watch.points-to-watch-gallery-list');



    Route::post('thaihealth-watch/register', 'FrontendController@postThaihealthWatchRegister')->name('thaihealth-watch.register');
    Route::post('thaihealth-watch/form', 'FrontendController@postThaihealthWatchForm')->name('thaihealth-watch.form');
    Route::post('request-media/front/create', 'RequestMediaController@postFrontCreate')->name('request-media-front.create');
    Route::post('document-download/front/update', 'FrontendController@postDownload')->name('document-download.update');
    Route::post('media-download/front/update', 'FrontendController@postMediaDownload')->name('media-download.update');
    Route::post('dol-download/front/files', 'FrontendController@postDolDownloadFiles')->name('dol-download.files');
    Route::post('dol-download/front/update', 'FrontendController@postDownloadCaseDol')->name('dol-download.update');

    
});
