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

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('categories', 'IndexController@getIndex')->name('admin.categories.index');
    Route::get('categories/create', 'IndexController@getCreate')->name('admin.categories.create');
    Route::post('categories/create', 'IndexController@postCreate')->name('admin.categories.create');
    Route::get('categories/edit/{id}', 'IndexController@getEdit')->name('admin.categories.edit');
    Route::post('categories/edit/{id}', 'IndexController@postEdit')->name('admin.categories.edit');
    Route::get('categories/delete/{id}', 'IndexController@getDelete')->name('admin.categories.delete');
    Route::post('categories/deleteAll', 'IndexController@postDeleteAll')->name('admin.categories.deleteAll');
    Route::get('categories/export/{fileType}', 'IndexController@getExport')->name('admin.categories.export');
});
