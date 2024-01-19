<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('single-page', 'IndexController@getIndex')->name('admin.single-page.index');
    Route::get('single-page/create', 'IndexController@getCreate')->name('admin.single-page.create');
    Route::post('single-page/create', 'IndexController@postCreate')->name('admin.single-page.create');
    Route::get('single-page/edit/{id}', 'IndexController@getEdit')->name('admin.single-page.edit');
    Route::post('single-page/edit/{id}', 'IndexController@postEdit')->name('admin.single-page.edit');
    Route::get('single-page/delete/{id}', 'IndexController@getDelete')->name('admin.single-page.delete');
    Route::post('single-page/deleteAll', 'IndexController@postDeleteAll')->name('admin.single-page.deleteAll');
    Route::get('single-page/reverse/{id}', 'IndexController@getReverse')->name('admin.single-page.reverse');
    Route::get('single-page-iframe', 'IndexController@getIndexiframe')->name('admin.single-page.index_iframe');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/single-page/{id}','FrontController@getPreview')->name('preview-single-page-chart');
    Route::get('front/single-page/{id}','FrontController@getFrontend')->name('single-page-frontend');
    Route::get('thaihealth-watch/single-page/{id}','FrontController@getThaihealthWatchFrontend')->name('single-page-thaihealth-watch');
});

