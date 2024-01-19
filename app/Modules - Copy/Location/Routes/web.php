<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('location', 'IndexController@getIndex')->name('admin.location.index');
    Route::get('location/create', 'IndexController@getCreate')->name('admin.location.create');
    Route::post('location/create', 'IndexController@postCreate')->name('admin.location.create');
    Route::get('location/edit/{id}', 'IndexController@getEdit')->name('admin.location.edit');
    Route::post('location/edit/{id}', 'IndexController@postEdit')->name('admin.location.edit');
    Route::get('location/delete/{id}', 'IndexController@getDelete')->name('admin.location.delete');
    Route::post('location/deleteAll', 'IndexController@postDeleteAll')->name('admin.location.deleteAll');
    Route::get('location/reverse/{id}', 'IndexController@getReverse')->name('admin.location.reverse');
});
