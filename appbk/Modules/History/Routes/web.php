<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('history', 'IndexController@getIndex')->name('admin.history.index');
    Route::get('history/create', 'IndexController@getCreate')->name('admin.history.create');
    Route::post('history/create', 'IndexController@postCreate')->name('admin.history.create');
    Route::get('history/edit/{id}', 'IndexController@getEdit')->name('admin.history.edit');
    Route::post('history/edit/{id}', 'IndexController@postEdit')->name('admin.history.edit');
    Route::get('history/delete/{id}', 'IndexController@getDelete')->name('admin.history.delete');
    Route::post('history/deleteAll', 'IndexController@postDeleteAll')->name('admin.history.deleteAll');
    Route::get('history/reverse/{id}', 'IndexController@getReverse')->name('admin.history.reverse');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/history','FrontController@getPreview')->name('preview-history-chart');
});

