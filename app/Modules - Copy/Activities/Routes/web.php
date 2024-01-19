<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('activities/', 'IndexController@getIndex')->name('admin.activities.index');
    Route::get('activities/create', 'IndexController@getCreate')->name('admin.activities.create');
    Route::post('activities/create', 'IndexController@postCreate')->name('admin.activities.create');
    Route::get('activities/edit/{id}', 'IndexController@getEdit')->name('admin.activities.edit');
    Route::post('activities/edit/{id}', 'IndexController@postEdit')->name('admin.activities.edit');
    Route::get('activities/delete/{id}', 'IndexController@getDelete')->name('admin.activities.delete');
    Route::post('activities/deleteAll', 'IndexController@postDeleteAll')->name('admin.activities.deleteAll');
    Route::get('activities/reverse/{id}', 'IndexController@getReverse')->name('admin.activities.reverse');
    Route::post('activities/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.activities.ajaxdeletegallery');
});


Route::group(['prefix'=>LaravelLocalization::setLocale(),'middleware'=>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/activities','FrontController@getPreview')->name('preview-activities');
});