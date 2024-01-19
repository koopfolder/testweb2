<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('menus', 'IndexController@getIndex')->name('admin.menus.index');
    Route::get('menus/create', 'IndexController@getCreate')->name('admin.menus.create');
    Route::post('menus/create', 'IndexController@postCreate')->name('admin.menus.create');
    Route::get('menus/edit/{id}', 'IndexController@getEdit')->name('admin.menus.edit');
    Route::post('menus/edit/{id}', 'IndexController@postEdit')->name('admin.menus.edit');
    Route::get('menus/delete/{id}', 'IndexController@getDelete')->name('admin.menus.delete');
    Route::get('menus/deleteImage/{id}', 'IndexController@getDeleteImage')->name('admin.menus.delete.image');
    Route::post('menus/deleteAll', 'IndexController@postDeleteAll')->name('admin.menus.deleteAll');
    Route::get('menus/export/{fileType}', 'IndexController@getExport')->name('admin.menus.export');
    Route::get('menus/preview/{url}', 'IndexController@getPreivew')->name('admin.menus.preview');
    Route::get('menus/reverse/{id}', 'IndexController@getReverse')->name('admin.menus.reverse');

});
