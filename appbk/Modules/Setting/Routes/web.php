<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
	Route::get('clear-cache', 'IndexController@getClearCache')->name('admin.clear-cache');
    Route::get('setting', 'IndexController@getIndex')->name('admin.setting.index');
    Route::post('setting', 'IndexController@postIndex')->name('admin.setting.index');

    Route::get('ncds-setting', 'IndexController@getNcdsIndex')->name('admin.ncds_setting.index');
    Route::post('ncds-setting', 'IndexController@postNcdsIndex')->name('admin.ncds_setting.index');

    Route::get('thaihealth-watch/setting', 'IndexController@getThaihealthWatchIndex')->name('admin.thaihealth_watch_setting.index');
    Route::post('thaihealth-watch/setting', 'IndexController@postThaihealthWatchIndex')->name('admin.thaihealth_watch_setting.index');

});
