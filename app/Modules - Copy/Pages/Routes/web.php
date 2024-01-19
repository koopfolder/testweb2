<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('pages', 'IndexController@getIndex')->name('admin.pages.index');
    Route::get('pages/create', 'IndexController@getCreate')->name('admin.pages.create');
    Route::post('pages/create', 'IndexController@postCreate')->name('admin.pages.create');
    Route::get('pages/edit/{id}', 'IndexController@getEdit')->name('admin.pages.edit');
    Route::post('pages/edit/{id}', 'IndexController@postEdit')->name('admin.pages.edit');
    Route::get('pages/delete/{id}', 'IndexController@getDelete')->name('admin.pages.delete');
    Route::post('pages/deleteAll', 'IndexController@postDeleteAll')->name('admin.pages.deleteAll');
    Route::get('pages/deleteImage/{id}/{collection}', 'IndexController@getDeleteImage')->name('admin.pages.deleteImage');
    Route::get('pages/deleteFile/{id}/', 'IndexController@getDeleteFile')->name('admin.pages.deleteFile');
    Route::get('pages/export/{fileType}', 'IndexController@getExport')->name('admin.pages.export');
    Route::get('pages/revision/{id}', 'IndexController@getRevision')->name('admin.pages.revision');
});
