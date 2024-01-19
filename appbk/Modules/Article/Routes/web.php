<?php

/*Backend*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('article', 'IndexController@getIndex')->name('admin.article.index');
    Route::get('article-iframe', 'IndexController@getIndexiframe')->name('admin.article.index_iframe');
    Route::get('article/create', 'IndexController@getCreate')->name('admin.article.create');
    Route::post('article/create', 'IndexController@postCreate')->name('admin.article.create');
    Route::get('article/edit/{id}', 'IndexController@getEdit')->name('admin.article.edit');
    Route::post('article/edit/{id}', 'IndexController@postEdit')->name('admin.article.edit');
    Route::get('article/delete/{id}', 'IndexController@getDelete')->name('admin.article.delete');
    Route::post('article/deleteAll', 'IndexController@postDeleteAll')->name('admin.article.deleteAll');
    Route::get('article/reverse/{id}', 'IndexController@getReverse')->name('admin.article.reverse');
    Route::post('article/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.article.ajaxdeletegallery');
    Route::post('article/delete-document', 'IndexController@postAjaxDeleteDocument')->name('admin.article.ajaxdeletedocument');

    Route::get('our-service', 'OurserviceController@getIndex')->name('admin.our-service.index');
    Route::get('our-service-iframe', 'OurserviceController@getIndexiframe')->name('admin.our-service.index_iframe');
    Route::get('our-service/create', 'OurserviceController@getCreate')->name('admin.our-service.create');
    Route::post('our-service/create', 'OurserviceController@postCreate')->name('admin.our-service.create');
    Route::get('our-service/edit/{id}', 'OurserviceController@getEdit')->name('admin.our-service.edit');
    Route::post('our-service/edit/{id}', 'OurserviceController@postEdit')->name('admin.our-service.edit');
    Route::get('our-service/delete/{id}', 'OurserviceController@getDelete')->name('admin.our-service.delete');
    Route::post('our-service/deleteAll', 'OurserviceController@postDeleteAll')->name('admin.our-service.deleteAll');
    Route::get('our-service/reverse/{id}', 'OurserviceController@getReverse')->name('admin.our-service.reverse');
    Route::post('our-service/delete-gallery', 'OurserviceController@postAjaxDeleteGallery')->name('admin.our-service.ajaxdeletegallery');
    Route::post('our-service/delete-document', 'OurserviceController@postAjaxDeleteDocument')->name('admin.our-service.ajaxdeletedocument');

    Route::get('sook-library', 'SooklibraryController@getIndex')->name('admin.sook-library.index');
    Route::get('sook-library-iframe', 'SooklibraryController@getIndexiframe')->name('admin.sook-library.index_iframe');
    Route::get('sook-library/create', 'SooklibraryController@getCreate')->name('admin.sook-library.create');
    Route::post('sook-library/create', 'SooklibraryController@postCreate')->name('admin.sook-library.create');
    Route::get('sook-library/edit/{id}', 'SooklibraryController@getEdit')->name('admin.sook-library.edit');
    Route::post('sook-library/edit/{id}', 'SooklibraryController@postEdit')->name('admin.sook-library.edit');
    Route::get('sook-library/delete/{id}', 'SooklibraryController@getDelete')->name('admin.sook-library.delete');
    Route::post('sook-library/deleteAll', 'SooklibraryController@postDeleteAll')->name('admin.sook-library.deleteAll');
    Route::get('sook-library/reverse/{id}', 'SooklibraryController@getReverse')->name('admin.sook-library.reverse');
    Route::post('sook-library/delete-gallery', 'SooklibraryController@postAjaxDeleteGallery')->name('admin.sook-library.ajaxdeletegallery');
    Route::post('sook-library/delete-document', 'SooklibraryController@postAjaxDeleteDocument')->name('admin.sook-library.ajaxdeletedocument');

    Route::get('training-course', 'TrainingcourseController@getIndex')->name('admin.training-course.index');
    Route::get('training-course-iframe', 'TrainingcourseController@getIndexiframe')->name('admin.training-course.index_iframe');
    Route::get('training-course/create', 'TrainingcourseController@getCreate')->name('admin.training-course.create');
    Route::post('training-course/create', 'TrainingcourseController@postCreate')->name('admin.training-course.create');
    Route::get('training-course/edit/{id}', 'TrainingcourseController@getEdit')->name('admin.training-course.edit');
    Route::post('training-course/edit/{id}', 'TrainingcourseController@postEdit')->name('admin.training-course.edit');
    Route::get('training-course/delete/{id}', 'TrainingcourseController@getDelete')->name('admin.training-course.delete');
    Route::post('training-course/deleteAll', 'TrainingcourseController@postDeleteAll')->name('admin.training-course.deleteAll');
    Route::get('training-course/reverse/{id}', 'TrainingcourseController@getReverse')->name('admin.training-course.reverse');
    Route::post('training-course/delete-gallery', 'TrainingcourseController@postAjaxDeleteGallery')->name('admin.training-course.ajaxdeletegallery');
    Route::post('training-course/delete-document', 'TrainingcourseController@postAjaxDeleteDocument')->name('admin.training-course.ajaxdeletedocument');


    Route::get('e-learning', 'ElearningController@getIndex')->name('admin.e-learning.index');
    Route::get('e-learning-iframe', 'ElearningController@getIndexiframe')->name('admin.e-learning.index_iframe');
    Route::get('e-learning/create', 'ElearningController@getCreate')->name('admin.e-learning.create');
    Route::post('e-learning/create', 'ElearningController@postCreate')->name('admin.e-learning.create');
    Route::get('e-learning/edit/{id}', 'ElearningController@getEdit')->name('admin.e-learning.edit');
    Route::post('e-learning/edit/{id}', 'ElearningController@postEdit')->name('admin.e-learning.edit');
    Route::get('e-learning/delete/{id}', 'ElearningController@getDelete')->name('admin.e-learning.delete');
    Route::post('e-learning/deleteAll', 'ElearningController@postDeleteAll')->name('admin.e-learning.deleteAll');
    Route::get('e-learning/reverse/{id}', 'ElearningController@getReverse')->name('admin.e-learning.reverse');
    Route::post('e-learning/delete-gallery', 'ElearningController@postAjaxDeleteGallery')->name('admin.e-learning.ajaxdeletegallery');
    Route::post('e-learning/delete-document', 'ElearningController@postAjaxDeleteDocument')->name('admin.e-learning.ajaxdeletedocument');

    Route::get('interesting-issues', 'InterestingissuesController@getIndex')->name('admin.interesting-issues.index');
    Route::get('interesting-issues-iframe', 'InterestingissuesController@getIndexiframe')->name('admin.interesting-issues.index_iframe');
    Route::get('interesting-issues/create', 'InterestingissuesController@getCreate')->name('admin.interesting-issues.create');
    Route::post('interesting-issues/create', 'InterestingissuesController@postCreate')->name('admin.interesting-issues.create');
    Route::get('interesting-issues/edit/{id}', 'InterestingissuesController@getEdit')->name('admin.interesting-issues.edit');
    Route::post('interesting-issues/edit/{id}', 'InterestingissuesController@postEdit')->name('admin.interesting-issues.edit');
    Route::get('interesting-issues/delete/{id}', 'InterestingissuesController@getDelete')->name('admin.interesting-issues.delete');
    Route::post('interesting-issues/deleteAll', 'InterestingissuesController@postDeleteAll')->name('admin.interesting-issues.deleteAll');
    Route::get('interesting-issues/reverse/{id}', 'InterestingissuesController@getReverse')->name('admin.interesting-issues.reverse');
    Route::post('interesting-issues/delete-gallery', 'InterestingissuesController@postAjaxDeleteGallery')->name('admin.interesting-issues.ajaxdeletegallery');
    Route::post('interesting-issues/delete-document', 'InterestingissuesController@postAjaxDeleteDocument')->name('admin.interesting-issues.ajaxdeletedocument');

    Route::get('articles-research', 'ArticlesResearchController@getIndex')->name('admin.articles-research.index');
    Route::get('articles-research-iframe', 'ArticlesResearchController@getIndexiframe')->name('admin.articles-research.index_iframe');
    Route::get('articles-research/create', 'ArticlesResearchController@getCreate')->name('admin.articles-research.create');
    Route::post('articles-research/create', 'ArticlesResearchController@postCreate')->name('admin.articles-research.create');
    Route::get('articles-research/edit/{id}', 'ArticlesResearchController@getEdit')->name('admin.articles-research.edit');
    Route::post('articles-research/edit/{id}', 'ArticlesResearchController@postEdit')->name('admin.articles-research.edit');
    Route::get('articles-research/delete/{id}', 'ArticlesResearchController@getDelete')->name('admin.articles-research.delete');
    Route::post('articles-research/deleteAll', 'ArticlesResearchController@postDeleteAll')->name('admin.articles-research.deleteAll');
    Route::get('articles-research/reverse/{id}', 'ArticlesResearchController@getReverse')->name('admin.articles-research.reverse');
    Route::post('articles-research/delete-gallery', 'ArticlesResearchController@postAjaxDeleteGallery')->name('admin.articles-research.ajaxdeletegallery');
    Route::post('articles-research/delete-document', 'ArticlesResearchController@postAjaxDeleteDocument')->name('admin.articles-research.ajaxdeletedocument');

    Route::get('include-statistics', 'IncludeStatisticsController@getIndex')->name('admin.include-statistics.index');
    Route::get('include-statistics-iframe', 'IncludeStatisticsController@getIndexiframe')->name('admin.include-statistics.index_iframe');
    Route::get('include-statistics/create', 'IncludeStatisticsController@getCreate')->name('admin.include-statistics.create');
    Route::post('include-statistics/create', 'IncludeStatisticsController@postCreate')->name('admin.include-statistics.create');
    Route::get('include-statistics/edit/{id}', 'IncludeStatisticsController@getEdit')->name('admin.include-statistics.edit');
    Route::post('include-statistics/edit/{id}', 'IncludeStatisticsController@postEdit')->name('admin.include-statistics.edit');
    Route::get('include-statistics/delete/{id}', 'IncludeStatisticsController@getDelete')->name('admin.include-statistics.delete');
    Route::post('include-statistics/deleteAll', 'IncludeStatisticsController@postDeleteAll')->name('admin.include-statistics.deleteAll');
    Route::get('include-statistics/reverse/{id}', 'IncludeStatisticsController@getReverse')->name('admin.include-statistics.reverse');
    Route::post('include-statistics/delete-gallery', 'IncludeStatisticsController@postAjaxDeleteGallery')->name('admin.include-statistics.ajaxdeletegallery');
    Route::post('include-statistics/delete-document', 'IncludeStatisticsController@postAjaxDeleteDocument')->name('admin.include-statistics.ajaxdeletedocument');

    Route::get('thaihealth-watch', 'ThaihealthWatchController@getIndex')->name('admin.thaihealth-watch.index');
    Route::get('thaihealth-watch-iframe', 'ThaihealthWatchController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/create', 'ThaihealthWatchController@getCreate')->name('admin.thaihealth-watch.create');
    Route::post('thaihealth-watch/create', 'ThaihealthWatchController@postCreate')->name('admin.thaihealth-watch.create');
    Route::get('thaihealth-watch/edit/{id}', 'ThaihealthWatchController@getEdit')->name('admin.thaihealth-watch.edit');
    Route::post('thaihealth-watch/edit/{id}', 'ThaihealthWatchController@postEdit')->name('admin.thaihealth-watch.edit');
    Route::get('thaihealth-watch/delete/{id}', 'ThaihealthWatchController@getDelete')->name('admin.thaihealth-watch.delete');
    Route::post('thaihealth-watch/deleteAll', 'ThaihealthWatchController@postDeleteAll')->name('admin.thaihealth-watch.deleteAll');
    Route::get('thaihealth-watch/reverse/{id}', 'ThaihealthWatchController@getReverse')->name('admin.thaihealth-watch.reverse');
    Route::post('thaihealth-watch/delete-gallery', 'ThaihealthWatchController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.ajaxdeletegallery');
    Route::post('thaihealth-watch/delete-document', 'ThaihealthWatchController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.ajaxdeletedocument');


    Route::get('thaihealth-watch/health-trends', 'ThaihealthWatchHealthTrendsController@getIndex')->name('admin.thaihealth-watch.health-trends.index');
    #Route::get('thaihealth-watch-health-trends-iframe', 'ThaihealthWatchHealthTrendsController@getIndexiframe')->name('admin.thaihealth-watch-health-trends.index_iframe');
    Route::get('thaihealth-watch/health-trends/create', 'ThaihealthWatchHealthTrendsController@getCreate')->name('admin.thaihealth-watch.health-trends.create');
    Route::post('thaihealth-watch/health-trends/create', 'ThaihealthWatchHealthTrendsController@postCreate')->name('admin.thaihealth-watch.health-trends.create');
    Route::get('thaihealth-watch/health-trends/edit/{id}', 'ThaihealthWatchHealthTrendsController@getEdit')->name('admin.thaihealth-watch.health-trends.edit');
    Route::post('thaihealth-watch/health-trends/edit/{id}', 'ThaihealthWatchHealthTrendsController@postEdit')->name('admin.thaihealth-watch.health-trends.edit');
    Route::get('thaihealth-watch/health-trends/delete/{id}', 'ThaihealthWatchHealthTrendsController@getDelete')->name('admin.thaihealth-watch.health-trends.delete');
    Route::post('thaihealth-watch/health-trends/deleteAll', 'ThaihealthWatchHealthTrendsController@postDeleteAll')->name('admin.thaihealth-watch.health-trends.deleteAll');
    Route::get('thaihealth-watch/health-trends/reverse/{id}', 'ThaihealthWatchHealthTrendsController@getReverse')->name('admin.thaihealth-watch.health-trends.reverse');
    Route::post('thaihealth-watch/health-trends/delete-gallery', 'ThaihealthWatchHealthTrendsController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.health-trends.ajaxdeletegallery');
    Route::post('thaihealth-watch/health-trends/delete-document', 'ThaihealthWatchHealthTrendsController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.health-trends.ajaxdeletedocument');

	


    Route::get('thaihealth-watch/form-generator', 'FormGeneratorController@getIndex')->name('admin.thaihealth-watch.form-generator.index');
    #Route::get('thaihealth-watch-iframe', 'FormGeneratorController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/form-generator/create', 'FormGeneratorController@getCreate')->name('admin.thaihealth-watch.form-generator.create');
    Route::post('thaihealth-watch/form-generator/create', 'FormGeneratorController@postCreate')->name('admin.thaihealth-watch.form-generator.create');
    Route::get('thaihealth-watch/form-generator/edit/{id}', 'FormGeneratorController@getEdit')->name('admin.thaihealth-watch.form-generator.edit');
    Route::post('thaihealth-watch/form-generator/edit/{id}', 'FormGeneratorController@postEdit')->name('admin.thaihealth-watch.form-generator.edit');
    Route::get('thaihealth-watch/form-generator/delete/{id}', 'FormGeneratorController@getDelete')->name('admin.thaihealth-watch.form-generator.delete');
    Route::post('thaihealth-watch/form-generator/deleteAll', 'FormGeneratorController@postDeleteAll')->name('admin.thaihealth-watch.form-generator.deleteAll');
    Route::get('thaihealth-watch/form-generator/reverse/{id}', 'FormGeneratorController@getReverse')->name('admin.thaihealth-watch.form-generator.reverse');
    Route::post('thaihealth-watch/form-generator/delete-gallery', 'FormGeneratorController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.form-generator.ajaxdeletegallery');
    Route::post('thaihealth-watch/form-generator/delete-document', 'FormGeneratorController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.form-generator.ajaxdeletedocument');
	Route::get('thaihealth-watch/form-generator/report/{id}', 'FormGeneratorController@getReport')->name('admin.thaihealth-watch.form-generator.report');
	Route::get('thaihealth-watch/form-generator/report-excel/{id}', 'FormGeneratorController@getExcelReport')->name('admin.thaihealth-watch.form-generator.report-excel');

	
    Route::get('thaihealth-watch/users', 'ThaihealthWatchUsersController@getIndex')->name('admin.thaihealth-watch.users.index');
	Route::get('thaihealth-watch/users/logs-send-email', 'ThaihealthWatchUsersController@getSendEmailIndex')->name('admin.thaihealth-watch.users.logs-send-email.index');
    // Route::get('thaihealth-watch-iframe', 'ThaihealthWatchController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/users/create', 'ThaihealthWatchUsersController@getCreate')->name('admin.thaihealth-watch.users.create');
    Route::get('thaihealth-watch/users/report', 'ThaihealthWatchUsersController@getExcelReport')->name('admin.thaihealth-watch.users.report');
	Route::post('thaihealth-watch/users/create', 'ThaihealthWatchUsersController@postCreate')->name('admin.thaihealth-watch.users.create');
    // Route::get('thaihealth-watch/edit/{id}', 'ThaihealthWatchController@getEdit')->name('admin.thaihealth-watch.edit');
    // Route::post('thaihealth-watch/edit/{id}', 'ThaihealthWatchController@postEdit')->name('admin.thaihealth-watch.edit');
    Route::get('thaihealth-watch/users/delete/{id}', 'ThaihealthWatchUsersController@getDelete')->name('admin.thaihealth-watch.users.delete');
    Route::post('thaihealth-watch/users/deleteAll', 'ThaihealthWatchUsersController@postDeleteAll')->name('admin.thaihealth-watch.users.deleteAll');
    // Route::get('thaihealth-watch/reverse/{id}', 'ThaihealthWatchController@getReverse')->name('admin.thaihealth-watch.reverse');
    // Route::post('thaihealth-watch/delete-gallery', 'ThaihealthWatchController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.ajaxdeletegallery');
    // Route::post('thaihealth-watch/delete-document', 'ThaihealthWatchController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.ajaxdeletedocument');

    Route::get('thaihealth-watch/panel-discussion', 'ThaihealthWatchPanelDiscussionController@getIndex')->name('admin.thaihealth-watch.panel-discussion.index');
    //Route::get('thaihealth-watch-iframe', 'ThaihealthWatchPanelDiscussionController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/panel-discussion/create', 'ThaihealthWatchPanelDiscussionController@getCreate')->name('admin.thaihealth-watch.panel-discussion.create');
    Route::post('thaihealth-watch/panel-discussion/create', 'ThaihealthWatchPanelDiscussionController@postCreate')->name('admin.thaihealth-watch.panel-discussion.create');
    Route::get('thaihealth-watch/panel-discussion/edit/{id}', 'ThaihealthWatchPanelDiscussionController@getEdit')->name('admin.thaihealth-watch.panel-discussion.edit');
    Route::post('thaihealth-watch/panel-discussion/edit/{id}', 'ThaihealthWatchPanelDiscussionController@postEdit')->name('admin.thaihealth-watch.panel-discussion.edit');
    Route::get('thaihealth-watch/panel-discussion/delete/{id}', 'ThaihealthWatchPanelDiscussionController@getDelete')->name('admin.thaihealth-watch.panel-discussion.delete');
    Route::post('thaihealth-watch/panel-discussion/deleteAll', 'ThaihealthWatchPanelDiscussionController@postDeleteAll')->name('admin.thaihealth-watch.panel-discussion.deleteAll');
    Route::get('thaihealth-watch/panel-discussion/reverse/{id}', 'ThaihealthWatchPanelDiscussionController@getReverse')->name('admin.thaihealth-watch.panel-discussion.reverse');
    Route::post('thaihealth-watch/panel-discussion/delete-gallery', 'ThaihealthWatchPanelDiscussionController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.panel-discussion.ajaxdeletegallery');
    Route::post('thaihealth-watch/panel-discussion/delete-document', 'ThaihealthWatchPanelDiscussionController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.panel-discussion.ajaxdeletedocument');


    Route::get('thaihealth-watch/points-to-watch-article', 'ThaihealthWatchPointsToWatchArticleController@getIndex')->name('admin.thaihealth-watch.points-to-watch-article.index');
    //Route::get('thaihealth-watch-iframe', 'ThaihealthWatchPointsToWatchArticleController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/points-to-watch-article/create', 'ThaihealthWatchPointsToWatchArticleController@getCreate')->name('admin.thaihealth-watch.  .create');
    Route::post('thaihealth-watch/points-to-watch-article/create', 'ThaihealthWatchPointsToWatchArticleController@postCreate')->name('admin.thaihealth-watch.points-to-watch-article.create');
    Route::get('thaihealth-watch/points-to-watch-article/edit/{id}', 'ThaihealthWatchPointsToWatchArticleController@getEdit')->name('admin.thaihealth-watch.points-to-watch-article.edit');
    Route::post('thaihealth-watch/points-to-watch-article/edit/{id}', 'ThaihealthWatchPointsToWatchArticleController@postEdit')->name('admin.thaihealth-watch.points-to-watch-article.edit');
    Route::get('thaihealth-watch/points-to-watch-article/delete/{id}', 'ThaihealthWatchPointsToWatchArticleController@getDelete')->name('admin.thaihealth-watch.points-to-watch-article.delete');
    Route::post('thaihealth-watch/points-to-watch-article/deleteAll', 'ThaihealthWatchPointsToWatchArticleController@postDeleteAll')->name('admin.thaihealth-watch.points-to-watch-article.deleteAll');
    Route::get('thaihealth-watch/points-to-watch-article/reverse/{id}', 'ThaihealthWatchPointsToWatchArticleController@getReverse')->name('admin.thaihealth-watch.points-to-watch-article.reverse');
    Route::post('thaihealth-watch/points-to-watch-article/delete-gallery', 'ThaihealthWatchPointsToWatchArticleController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.points-to-watch-article.ajaxdeletegallery');
    Route::post('thaihealth-watch/points-to-watch-article/delete-document', 'ThaihealthWatchPointsToWatchArticleController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.points-to-watch-article.ajaxdeletedocument');

    Route::get('thaihealth-watch/points-to-watch-video', 'ThaihealthWatchPointsToWatchVideoController@getIndex')->name('admin.thaihealth-watch.points-to-watch-video.index');
    //Route::get('thaihealth-watch-iframe', 'ThaihealthWatchPointsToWatchVideoController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/points-to-watch-video/create', 'ThaihealthWatchPointsToWatchVideoController@getCreate')->name('admin.thaihealth-watch.  .create');
    Route::post('thaihealth-watch/points-to-watch-video/create', 'ThaihealthWatchPointsToWatchVideoController@postCreate')->name('admin.thaihealth-watch.points-to-watch-video.create');
    Route::get('thaihealth-watch/points-to-watch-video/edit/{id}', 'ThaihealthWatchPointsToWatchVideoController@getEdit')->name('admin.thaihealth-watch.points-to-watch-video.edit');
    Route::post('thaihealth-watch/points-to-watch-video/edit/{id}', 'ThaihealthWatchPointsToWatchVideoController@postEdit')->name('admin.thaihealth-watch.points-to-watch-video.edit');
    Route::get('thaihealth-watch/points-to-watch-video/delete/{id}', 'ThaihealthWatchPointsToWatchVideoController@getDelete')->name('admin.thaihealth-watch.points-to-watch-video.delete');
    Route::post('thaihealth-watch/points-to-watch-video/deleteAll', 'ThaihealthWatchPointsToWatchVideoController@postDeleteAll')->name('admin.thaihealth-watch.points-to-watch-video.deleteAll');
    Route::get('thaihealth-watch/points-to-watch-video/reverse/{id}', 'ThaihealthWatchPointsToWatchVideoController@getReverse')->name('admin.thaihealth-watch.points-to-watch-video.reverse');
    Route::post('thaihealth-watch/points-to-watch-video/delete-gallery', 'ThaihealthWatchPointsToWatchVideoController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.points-to-watch-video.ajaxdeletegallery');
    Route::post('thaihealth-watch/points-to-watch-video/delete-document', 'ThaihealthWatchPointsToWatchVideoController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.points-to-watch-video.ajaxdeletedocument');

    Route::get('thaihealth-watch/points-to-watch-gallery', 'ThaihealthWatchPointsToWatchGalleryController@getIndex')->name('admin.thaihealth-watch.points-to-watch-gallery.index');
    //Route::get('thaihealth-watch-iframe', 'ThaihealthWatchPointsToWatchGalleryController@getIndexiframe')->name('admin.thaihealth-watch.index_iframe');
    Route::get('thaihealth-watch/points-to-watch-gallery/create', 'ThaihealthWatchPointsToWatchGalleryController@getCreate')->name('admin.thaihealth-watch.  .create');
    Route::post('thaihealth-watch/points-to-watch-gallery/create', 'ThaihealthWatchPointsToWatchGalleryController@postCreate')->name('admin.thaihealth-watch.points-to-watch-gallery.create');
    Route::get('thaihealth-watch/points-to-watch-gallery/edit/{id}', 'ThaihealthWatchPointsToWatchGalleryController@getEdit')->name('admin.thaihealth-watch.points-to-watch-gallery.edit');
    Route::post('thaihealth-watch/points-to-watch-gallery/edit/{id}', 'ThaihealthWatchPointsToWatchGalleryController@postEdit')->name('admin.thaihealth-watch.points-to-watch-gallery.edit');
    Route::get('thaihealth-watch/points-to-watch-gallery/delete/{id}', 'ThaihealthWatchPointsToWatchGalleryController@getDelete')->name('admin.thaihealth-watch.points-to-watch-gallery.delete');
    Route::post('thaihealth-watch/points-to-watch-gallery/deleteAll', 'ThaihealthWatchPointsToWatchGalleryController@postDeleteAll')->name('admin.thaihealth-watch.points-to-watch-gallery.deleteAll');
    Route::get('thaihealth-watch/points-to-watch-gallery/reverse/{id}', 'ThaihealthWatchPointsToWatchGalleryController@getReverse')->name('admin.thaihealth-watch.points-to-watch-gallery.reverse');
    Route::post('thaihealth-watch/points-to-watch-gallery/delete-gallery', 'ThaihealthWatchPointsToWatchGalleryController@postAjaxDeleteGallery')->name('admin.thaihealth-watch.points-to-watch-gallery.ajaxdeletegallery');
    Route::post('thaihealth-watch/points-to-watch-gallery/delete-document', 'ThaihealthWatchPointsToWatchGalleryController@postAjaxDeleteDocument')->name('admin.thaihealth-watch.points-to-watch-gallery.ajaxdeletedocument');

    Route::get('event-calendar', 'EventCalendarController@getIndex')->name('admin.event-calendar.index');
    Route::get('event-calendar-iframe', 'EventCalendarController@getIndexiframe')->name('admin.event-calendar.index_iframe');
    Route::get('event-calendar/create', 'EventCalendarController@getCreate')->name('admin.event-calendar.create');
    Route::post('event-calendar/create', 'EventCalendarController@postCreate')->name('admin.event-calendar.create');
    Route::get('event-calendar/edit/{id}', 'EventCalendarController@getEdit')->name('admin.event-calendar.edit');
    Route::post('event-calendar/edit/{id}', 'EventCalendarController@postEdit')->name('admin.event-calendar.edit');
    Route::get('event-calendar/delete/{id}', 'EventCalendarController@getDelete')->name('admin.event-calendar.delete');
    Route::post('event-calendar/deleteAll', 'EventCalendarController@postDeleteAll')->name('admin.event-calendar.deleteAll');
    Route::get('event-calendar/reverse/{id}', 'EventCalendarController@getReverse')->name('admin.event-calendar.reverse');
    Route::post('event-calendar/delete-gallery', 'EventCalendarController@postAjaxDeleteGallery')->name('admin.event-calendar.ajaxdeletegallery');
    Route::post('event-calendar/delete-document', 'EventCalendarController@postAjaxDeleteDocument')->name('admin.event-calendar.ajaxdeletedocument');

    Route::get('learning-area-creates-direct-experience', 'LearningAreaCreatesDirectExperienceController@getIndex')->name('admin.learning-area-creates-direct-experience.index');
    Route::get('learning-area-creates-direct-experience-iframe', 'LearningAreaCreatesDirectExperienceController@getIndexiframe')->name('admin.learning-area-creates-direct-experience.index_iframe');
    Route::get('learning-area-creates-direct-experience/create', 'LearningAreaCreatesDirectExperienceController@getCreate')->name('admin.learning-area-creates-direct-experience.create');
    Route::post('learning-area-creates-direct-experience/create', 'LearningAreaCreatesDirectExperienceController@postCreate')->name('admin.learning-area-creates-direct-experience.create');
    Route::get('learning-area-creates-direct-experience/edit/{id}', 'LearningAreaCreatesDirectExperienceController@getEdit')->name('admin.learning-area-creates-direct-experience.edit');
    Route::post('learning-area-creates-direct-experience/edit/{id}', 'LearningAreaCreatesDirectExperienceController@postEdit')->name('admin.learning-area-creates-direct-experience.edit');
    Route::get('learning-area-creates-direct-experience/delete/{id}', 'LearningAreaCreatesDirectExperienceController@getDelete')->name('admin.learning-area-creates-direct-experience.delete');
    Route::post('learning-area-creates-direct-experience/deleteAll', 'LearningAreaCreatesDirectExperienceController@postDeleteAll')->name('admin.learning-area-creates-direct-experience.deleteAll');
    Route::get('learning-area-creates-direct-experience/reverse/{id}', 'LearningAreaCreatesDirectExperienceController@getReverse')->name('admin.learning-area-creates-direct-experience.reverse');
    Route::post('learning-area-creates-direct-experience/delete-gallery', 'LearningAreaCreatesDirectExperienceController@postAjaxDeleteGallery')->name('admin.learning-area-creates-direct-experience.ajaxdeletegallery');
    Route::post('learning-area-creates-direct-experience/delete-document', 'LearningAreaCreatesDirectExperienceController@postAjaxDeleteDocument')->name('admin.learning-area-creates-direct-experience.ajaxdeletedocument');

    Route::get('health-literacy-category', 'ArticleCategoryController@getIndex')->name('admin.health-literacy-category.index');
    Route::get('health-literacy-category-iframe', 'ArticleCategoryController@getIndexiframe')->name('admin.health-literacy-category.index_iframe');
    Route::get('health-literacy-category/create', 'ArticleCategoryController@getCreate')->name('admin.health-literacy-category.create');
    Route::post('health-literacy-category/create', 'ArticleCategoryController@postCreate')->name('admin.health-literacy-category.create');
    Route::get('health-literacy-category/edit/{id}', 'ArticleCategoryController@getEdit')->name('admin.health-literacy-category.edit');
    Route::post('health-literacy-category/edit/{id}', 'ArticleCategoryController@postEdit')->name('admin.health-literacy-category.edit');
    Route::get('health-literacy-category/delete/{id}', 'ArticleCategoryController@getDelete')->name('admin.health-literacy-category.delete');
    Route::post('health-literacy-category/deleteAll', 'ArticleCategoryController@postDeleteAll')->name('admin.health-literacy-category.deleteAll');
    Route::get('health-literacy-category/reverse/{id}', 'ArticleCategoryController@getReverse')->name('admin.health-literacy-category.reverse');
    Route::post('health-literacy-category/delete-gallery', 'ArticleCategoryController@postAjaxDeleteGallery')->name('admin.health-literacy-category.ajaxdeletegallery');
    Route::post('health-literacy-category/delete-document', 'ArticleCategoryController@postAjaxDeleteDocument')->name('admin.health-literacy-category.ajaxdeletedocument');

    Route::get('health-literacy-main', 'HealthLiteracyMainController@getIndex')->name('admin.health-literacy-main.index');
    Route::get('health-literacy-main-iframe', 'HealthLiteracyMainController@getIndexiframe')->name('admin.health-literacy-main.index_iframe');
    Route::get('health-literacy-main/create', 'HealthLiteracyMainController@getCreate')->name('admin.health-literacy-main.create');
    Route::post('health-literacy-main/create', 'HealthLiteracyMainController@postCreate')->name('admin.health-literacy-main.create');
    Route::get('health-literacy-main/edit/{id}', 'HealthLiteracyMainController@getEdit')->name('admin.health-literacy-main.edit');
    Route::post('health-literacy-main/edit/{id}', 'HealthLiteracyMainController@postEdit')->name('admin.health-literacy-main.edit');
    Route::get('health-literacy-main/delete/{id}', 'HealthLiteracyMainController@getDelete')->name('admin.health-literacy-main.delete');
    Route::post('health-literacy-main/deleteAll', 'HealthLiteracyMainController@postDeleteAll')->name('admin.health-literacy-main.deleteAll');
    Route::get('health-literacy-main/reverse/{id}', 'HealthLiteracyMainController@getReverse')->name('admin.health-literacy-main.reverse');
    Route::post('health-literacy-main/delete-gallery', 'HealthLiteracyMainController@postAjaxDeleteGallery')->name('admin.health-literacy-main.ajaxdeletegallery');
    Route::post('health-literacy-main/delete-document', 'HealthLiteracyMainController@postAjaxDeleteDocument')->name('admin.health-literacy-main.ajaxdeletedocument');

    Route::get('health-literacy', 'HealthLiteracyController@getIndex')->name('admin.health-literacy.index');
    Route::get('health-literacy-iframe', 'HealthLiteracyController@getIndexiframe')->name('admin.health-literacy.index_iframe');
    Route::get('health-literacy/create', 'HealthLiteracyController@getCreate')->name('admin.health-literacy.create');
    Route::post('health-literacy/create', 'HealthLiteracyController@postCreate')->name('admin.health-literacy.create');
    Route::get('health-literacy/edit/{id}', 'HealthLiteracyController@getEdit')->name('admin.health-literacy.edit');
    Route::post('health-literacy/edit/{id}', 'HealthLiteracyController@postEdit')->name('admin.health-literacy.edit');
    Route::get('health-literacy/delete/{id}', 'HealthLiteracyController@getDelete')->name('admin.health-literacy.delete');
    Route::post('health-literacy/deleteAll', 'HealthLiteracyController@postDeleteAll')->name('admin.health-literacy.deleteAll');
    Route::get('health-literacy/reverse/{id}', 'HealthLiteracyController@getReverse')->name('admin.health-literacy.reverse');
    Route::post('health-literacy/delete-gallery', 'HealthLiteracyController@postAjaxDeleteGallery')->name('admin.health-literacy.ajaxdeletegallery');
    Route::post('health-literacy/delete-document', 'HealthLiteracyController@postAjaxDeleteDocument')->name('admin.health-literacy.ajaxdeletedocument');

    Route::get('ncds-1', 'Ncds1Controller@getIndex')->name('admin.ncds-1.index');
    Route::get('ncds-1-iframe', 'Ncds1Controller@getIndexiframe')->name('admin.ncds-1.index_iframe');
    Route::get('ncds-1/create', 'Ncds1Controller@getCreate')->name('admin.ncds-1.create');
    Route::post('ncds-1/create', 'Ncds1Controller@postCreate')->name('admin.ncds-1.create');
    Route::get('ncds-1/edit/{id}', 'Ncds1Controller@getEdit')->name('admin.ncds-1.edit');
    Route::post('ncds-1/edit/{id}', 'Ncds1Controller@postEdit')->name('admin.ncds-1.edit');
    Route::get('ncds-1/delete/{id}', 'Ncds1Controller@getDelete')->name('admin.ncds-1.delete');
    Route::post('ncds-1/deleteAll', 'Ncds1Controller@postDeleteAll')->name('admin.ncds-1.deleteAll');
    Route::get('ncds-1/reverse/{id}', 'Ncds1Controller@getReverse')->name('admin.ncds-1.reverse');
    Route::post('ncds-1/delete-gallery', 'Ncds1Controller@postAjaxDeleteGallery')->name('admin.ncds-1.ajaxdeletegallery');
    Route::post('ncds-1/delete-document', 'Ncds1Controller@postAjaxDeleteDocument')->name('admin.ncds-1.ajaxdeletedocument');

    Route::get('ncds-2', 'Ncds2Controller@getIndex')->name('admin.ncds-2.index');
    Route::get('ncds-2-iframe', 'Ncds2Controller@getIndexiframe')->name('admin.ncds-2.index_iframe');
    Route::get('ncds-2/create', 'Ncds2Controller@getCreate')->name('admin.ncds-2.create');
    Route::post('ncds-2/create', 'Ncds2Controller@postCreate')->name('admin.ncds-2.create');
    Route::get('ncds-2/edit/{id}', 'Ncds2Controller@getEdit')->name('admin.ncds-2.edit');
    Route::post('ncds-2/edit/{id}', 'Ncds2Controller@postEdit')->name('admin.ncds-2.edit');
    Route::get('ncds-2/delete/{id}', 'Ncds2Controller@getDelete')->name('admin.ncds-2.delete');
    Route::post('ncds-2/deleteAll', 'Ncds2Controller@postDeleteAll')->name('admin.ncds-2.deleteAll');
    Route::get('ncds-2/reverse/{id}', 'Ncds2Controller@getReverse')->name('admin.ncds-2.reverse');
    Route::post('ncds-2/delete-gallery', 'Ncds2Controller@postAjaxDeleteGallery')->name('admin.ncds-2.ajaxdeletegallery');
    Route::post('ncds-2/delete-document', 'Ncds2Controller@postAjaxDeleteDocument')->name('admin.ncds-2.ajaxdeletedocument');

    Route::get('ncds-4', 'Ncds4Controller@getIndex')->name('admin.ncds-4.index');
    Route::get('ncds-4-iframe', 'Ncds4Controller@getIndexiframe')->name('admin.ncds-4.index_iframe');
    Route::get('ncds-4/create', 'Ncds4Controller@getCreate')->name('admin.ncds-4.create');
    Route::post('ncds-4/create', 'Ncds4Controller@postCreate')->name('admin.ncds-4.create');
    Route::get('ncds-4/edit/{id}', 'Ncds4Controller@getEdit')->name('admin.ncds-4.edit');
    Route::post('ncds-4/edit/{id}', 'Ncds4Controller@postEdit')->name('admin.ncds-4.edit');
    Route::get('ncds-4/delete/{id}', 'Ncds4Controller@getDelete')->name('admin.ncds-4.delete');
    Route::post('ncds-4/deleteAll', 'Ncds4Controller@postDeleteAll')->name('admin.ncds-4.deleteAll');
    Route::get('ncds-4/reverse/{id}', 'Ncds4Controller@getReverse')->name('admin.ncds-4.reverse');
    Route::post('ncds-4/delete-gallery', 'Ncds4Controller@postAjaxDeleteGallery')->name('admin.ncds-4.ajaxdeletegallery');
    Route::post('ncds-4/delete-document', 'Ncds4Controller@postAjaxDeleteDocument')->name('admin.ncds-4.ajaxdeletedocument');

    Route::get('ncds-6', 'Ncds6Controller@getIndex')->name('admin.ncds-6.index');
    Route::get('ncds-6-iframe', 'Ncds6Controller@getIndexiframe')->name('admin.ncds-6.index_iframe');
    Route::get('ncds-6/create', 'Ncds6Controller@getCreate')->name('admin.ncds-6.create');
    Route::post('ncds-6/create', 'Ncds6Controller@postCreate')->name('admin.ncds-6.create');
    Route::get('ncds-6/edit/{id}', 'Ncds6Controller@getEdit')->name('admin.ncds-6.edit');
    Route::post('ncds-6/edit/{id}', 'Ncds6Controller@postEdit')->name('admin.ncds-6.edit');
    Route::get('ncds-6/delete/{id}', 'Ncds6Controller@getDelete')->name('admin.ncds-6.delete');
    Route::post('ncds-6/deleteAll', 'Ncds6Controller@postDeleteAll')->name('admin.ncds-6.deleteAll');
    Route::get('ncds-6/reverse/{id}', 'Ncds6Controller@getReverse')->name('admin.ncds-6.reverse');
    Route::post('ncds-6/delete-gallery', 'Ncds6Controller@postAjaxDeleteGallery')->name('admin.ncds-6.ajaxdeletegallery');
    Route::post('ncds-6/delete-document', 'Ncds6Controller@postAjaxDeleteDocument')->name('admin.ncds-6.ajaxdeletedocument');




});

//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::group([], function(){
    Route::get('preview/news-events/detail/{id}','NewsController@getDetail')->name('preview-news-events-detail');

    /* News Events */
    Route::get('news-events/list','NewsController@getListNews')->name('list-news-event');
    Route::get('news-events/{slug}','NewsController@getDetailNewsEvent')->name('news-event-detail');

    /* Interestingissues */
    Route::get('interesting-issues/list','InterestingissuesController@getListInterestingissues')->name('list-interestingissues');
    Route::get('interesting-issues/article/{slug}','InterestingissuesController@getDetailInterestingissuesArticle')->name('interestingissues-article-detail');
    Route::get('interesting-issues/{slug}','InterestingissuesController@getDetailInterestingissuesArticle')->name('interestingissues-article-detail-2');
    Route::get('interesting-issues/media/{id}','InterestingissuesController@getDetailInterestingissuesMedia')->name('interestingissues-media-detail');

    /* ArticlesResearch */
    Route::get('articles-research/list','ArticlesResearchController@getListArticlesResearch')->name('list-articles-research');

    /* ThaihealthWatch */
    // Route::get('thaihealth-watch/list','ThaihealthWatchController@getListThaihealthWatch')->name('list-thaihealth-watch');

    /* EventCalendar */
    Route::get('event-calendar/list','EventCalendarController@getListEventCalendar')->name('list-event-calendar');

    /* IncludeStatistics */
    Route::get('include-statistics/list','IncludeStatisticsController@getListIncludeStatistics')->name('list-include-statistics');

    /* HealthLiteracy */
    Route::get('health-literacy/category/list','ArticleCategoryController@getListArticleCategory')->name('list-health-literacy-category');

    /* HealthLiteracy */
    Route::get('health-literacy/list/{category_id}','HealthLiteracyController@getListHealthLiteracy')->name('list-health-literacy');
    Route::get('health-literacy/list2/{category_id}','HealthLiteracyController@getListHealthLiteracy2')->name('list-health-literacy2');
	Route::get('health-literacy/list3/all','HealthLiteracyController@getListHealthLiteracy3')->name('list-health-literacy3');
	

    /* Exhibition */
    Route::get('revolving-exhibition/list','\App\Modules\Exhibition\Http\Controllers\FrontendController@getListRevolvingExhibition')->name('revolving-exhibition-list');
    Route::get('permanent-exhibition/list','\App\Modules\Exhibition\Http\Controllers\FrontendController@getListPermanentExhibition')->name('permanent-exhibition-list');
    Route::get('traveling-exhibition/list','\App\Modules\Exhibition\Http\Controllers\FrontendController@getListTravelingExhibition')->name('traveling-exhibition-list');
    Route::get('online-exhibition/list','\App\Modules\Exhibition\Http\Controllers\FrontendController@getListOnlineExhibition')->name('online-exhibition-list');
    Route::get('exhibition-borrowed/list','\App\Modules\Exhibition\Http\Controllers\FrontendController@getListExhibitionBorrowed')->name('exhibition-borrowed-list');
    Route::get('request-media/front','\App\Modules\Api\Http\Controllers\RequestMediaController@getRequestMediaFront')->name('request-media-front');

});


//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::group([], function()
{
	$main_menu_case_route = ThrcHelpers::getMenu(['position'=>'header']);
	$left_menu_case_route = ThrcHelpers::getMenu(['position'=>'footer-left']);
	$right_menu_case_route = ThrcHelpers::getMenu(['position'=>'footer-right']);
    $time_cache  =  ThrcHelpers::time_cache(15);

	/* Main */
	if($main_menu_case_route->count()){
		//Level 1 
		foreach ($main_menu_case_route AS $key_level1 =>$value_level1){

			//dd($value_level1);
			switch ($value_level1['layout']){
				case 'news_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'our_service_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'sook_library_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;	
				case 'training_course_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'e_learning_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'interesting_issues_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'revolving_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'permanent_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'traveling_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'exhibition_borrowed_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				default:
					
					break;
			}

			$check_childrens = collect($value_level1['childrens']);   
			if($check_childrens->count()){
				//Level 2 
				foreach($value_level1['childrens'] AS $key_level2=>$value_level2){

					switch ($value_level2['layout']){
						case 'news_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'our_service_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'sook_library_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;	
						case 'training_course_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'e_learning_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'interesting_issues_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'revolving_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'permanent_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'traveling_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'exhibition_borrowed_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						default:
							
							break;
					}



					if (Cache::has('menu_children'.$value_level2->id)){
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);
                    }else{

                        $menu_level3 = $value_level2->FrontChildren()->get();
                        Cache::put('menu_children'.$value_level2->id,$menu_level3,$time_cache);
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);
                    }


					if($menu_level3->count()){
						foreach($menu_level3 AS $key_cmenu_level3 =>$value_menu_level3){

							//dd($value_menu_level3);
							
							switch ($value_menu_level3['layout']){
								case 'news_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'our_service_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'sook_library_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;	
								case 'training_course_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'e_learning_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'interesting_issues_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'revolving_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'permanent_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'traveling_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'exhibition_borrowed_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								default:
									
									break;
							}

						}/*Endforeach Level3 */
					}/*Endif Level3 */
				}/* Endforeach Level2 */
			}/*Endif level 2 */
		}/*Endforeach level 1 */
	}/*End if level1 */


	/* Left */
	if($left_menu_case_route->count()){
		//Level 1 
		foreach ($left_menu_case_route AS $key_level1 =>$value_level1){

			//dd($value_level1);
			switch ($value_level1['layout']){
				case 'news_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'our_service_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'sook_library_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;	
				case 'training_course_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'e_learning_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'interesting_issues_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'revolving_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'permanent_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'traveling_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'exhibition_borrowed_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				default:
					
					break;
			}

			$check_childrens = collect($value_level1['childrens']);   
			if($check_childrens->count()){
				//Level 2 
				foreach($value_level1['childrens'] AS $key_level2=>$value_level2){

					switch ($value_level2['layout']){
						case 'news_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'our_service_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'sook_library_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;	
						case 'training_course_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'e_learning_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'interesting_issues_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'revolving_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'permanent_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'traveling_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'exhibition_borrowed_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						default:
							
							break;
					}


					if (Cache::has('menu_children'.$value_level2->id)){
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);
                    }else{

                        $menu_level3 = $value_level2->FrontChildren()->get();
                        Cache::put('menu_children'.$value_level2->id,$menu_level3,$time_cache);
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);

                    }

					if($menu_level3->count()){
						foreach($menu_level3 AS $key_cmenu_level3 =>$value_menu_level3){

							//dd($value_menu_level3);
							
							switch ($value_menu_level3['layout']){
								case 'news_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'our_service_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'sook_library_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;	
								case 'training_course_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'e_learning_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'interesting_issues_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'revolving_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'permanent_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'traveling_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'exhibition_borrowed_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								default:
									
									break;
							}

						}/*Endforeach Level3 */
					}/*Endif Level3 */
				}/* Endforeach Level2 */
			}/*Endif level 2 */
		}/*Endforeach level 1 */
	}/*End if level1 */



	/* Right */
	if($right_menu_case_route->count()){
		//Level 1 
		foreach ($right_menu_case_route AS $key_level1 =>$value_level1){

			//dd($value_level1);
			switch ($value_level1['layout']){
				case 'news_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'our_service_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'sook_library_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;	
				case 'training_course_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'e_learning_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'interesting_issues_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'revolving_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'permanent_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'traveling_exhibition_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				case 'exhibition_borrowed_list':
					Route::get($value_level1['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level1['slug']);
					break;
				default:
					
					break;
			}

			$check_childrens = collect($value_level1['childrens']);   
			if($check_childrens->count()){
				//Level 2 
				foreach($value_level1['childrens'] AS $key_level2=>$value_level2){

					switch ($value_level2['layout']){
						case 'news_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'our_service_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'sook_library_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;	
						case 'training_course_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'e_learning_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'interesting_issues_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'revolving_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'permanent_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'traveling_exhibition_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						case 'exhibition_borrowed_list':
							Route::get($value_level2['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_level2['slug']);
							break;
						default:
							
							break;
					}


					if (Cache::has('menu_children'.$value_level2->id)){
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);
                    }else{

                        $menu_level3 = $value_level2->FrontChildren()->get();
                        Cache::put('menu_children'.$value_level2->id,$menu_level3,$time_cache);
                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);

                    }
                    
					if($menu_level3->count()){
						foreach($menu_level3 AS $key_cmenu_level3 =>$value_menu_level3){

							//dd($value_menu_level3);
							
							switch ($value_menu_level3['layout']){
								case 'news_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'our_service_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'sook_library_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;	
								case 'training_course_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'e_learning_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'interesting_issues_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'revolving_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'permanent_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'traveling_exhibition_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								case 'exhibition_borrowed_list':
									Route::get($value_menu_level3['slug'].'/{slug}','\App\Modules\Article\Http\Controllers\NewsController@getDetailArticle')->name($value_menu_level3['slug']);
									break;
								default:
									
									break;
							}

						}/*Endforeach Level3 */
					}/*Endif Level3 */
				}/* Endforeach Level2 */
			}/*Endif level 2 */
		}/*Endforeach level 1 */
	}/*End if level1 */

});



