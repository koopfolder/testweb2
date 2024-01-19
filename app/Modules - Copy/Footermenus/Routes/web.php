<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('footermenus/left', 'LeftController@getIndex')->name('admin.footermenus.left.index');
    Route::get('footermenus/left/create', 'LeftController@getCreate')->name('admin.footermenus.left.create');
    Route::post('footermenus/left/create', 'LeftController@postCreate')->name('admin.footermenus.left.create');
    Route::get('footermenus/left/edit/{id}', 'LeftController@getEdit')->name('admin.footermenus.left.edit');
    Route::post('footermenus/left/edit/{id}', 'LeftController@postEdit')->name('admin.footermenus.left.edit');
    Route::get('footermenus/left/delete/{id}', 'LeftController@getDelete')->name('admin.footermenus.left.delete');
    Route::get('footermenus/left/deleteImage/{id}', 'LeftController@getDeleteImage')->name('admin.footermenus.left.delete.image');
    Route::post('footermenus/left/deleteAll', 'LeftController@postDeleteAll')->name('admin.footermenus.left.deleteAll');
    Route::get('footermenus/left/export/{fileType}', 'LeftController@getExport')->name('admin.footermenus.left.export');
    Route::get('footermenus/left/preview/{url}', 'LeftController@getPreivew')->name('admin.footermenus.left.preview');
    Route::get('footermenus/left/reverse/{id}', 'LeftController@getReverse')->name('admin.footermenus.left.reverse');


    Route::get('footermenus/right', 'RightController@getIndex')->name('admin.footermenus.right.index');
    Route::get('footermenus/right/create', 'RightController@getCreate')->name('admin.footermenus.right.create');
    Route::post('footermenus/right/create', 'RightController@postCreate')->name('admin.footermenus.right.create');
    Route::get('footermenus/right/edit/{id}', 'RightController@getEdit')->name('admin.footermenus.right.edit');
    Route::post('footermenus/right/edit/{id}', 'RightController@postEdit')->name('admin.footermenus.right.edit');
    Route::get('footermenus/right/delete/{id}', 'RightController@getDelete')->name('admin.footermenus.right.delete');
    Route::get('footermenus/right/deleteImage/{id}', 'RightController@getDeleteImage')->name('admin.footermenus.right.delete.image');
    Route::post('footermenus/right/deleteAll', 'RightController@postDeleteAll')->name('admin.footermenus.right.deleteAll');
    Route::get('footermenus/right/export/{fileType}', 'RightController@getExport')->name('admin.footermenus.right.export');
    Route::get('footermenus/right/preview/{url}', 'RightController@getPreivew')->name('admin.footermenus.right.preview');
    Route::get('footermenus/right/reverse/{id}', 'RightController@getReverse')->name('admin.footermenus.right.reverse');

});
