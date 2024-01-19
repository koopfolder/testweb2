<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('link', 'IndexController@getIndex')->name('admin.link.index');
    Route::get('link/create', 'IndexController@getCreate')->name('admin.link.create');
    Route::post('link/create', 'IndexController@postCreate')->name('admin.link.create');
    Route::get('link/edit/{id}', 'IndexController@getEdit')->name('admin.link.edit');
    Route::post('link/edit/{id}', 'IndexController@postEdit')->name('admin.link.edit');
    Route::get('link/delete/{id}', 'IndexController@getDelete')->name('admin.link.delete');
    Route::post('link/deleteAll', 'IndexController@postDeleteAll')->name('admin.link.deleteAll');
});
