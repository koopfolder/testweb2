<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('groupstructure/', 'IndexController@getIndex')->name('admin.groupstructure.index');
    Route::get('groupstructure/create', 'IndexController@getCreate')->name('admin.groupstructure.create');
    Route::post('groupstructure/create', 'IndexController@postCreate')->name('admin.groupstructure.create');
    Route::get('groupstructure/edit/{id}', 'IndexController@getEdit')->name('admin.groupstructure.edit');
    Route::post('groupstructure/edit/{id}', 'IndexController@postEdit')->name('admin.groupstructure.edit');
    Route::get('groupstructure/delete/{id}', 'IndexController@getDelete')->name('admin.groupstructure.delete');
    Route::post('groupstructure/deleteAll', 'IndexController@postDeleteAll')->name('admin.groupstructure.deleteAll');
    Route::get('groupstructure/reverse/{id}', 'IndexController@getReverse')->name('admin.groupstructure.reverse');
    Route::post('groupstructure/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.groupstructure.ajaxdeletegallery');
});


Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/group-structure','FrontController@getPreview')->name('preview-group-structure');
});

