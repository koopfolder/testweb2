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
    Route::get('roles', 'IndexController@getIndex')->name('admin.roles.index');
    Route::get('roles/create', 'IndexController@getCreate')->name('admin.roles.create');
    Route::post('roles/create', 'IndexController@postCreate')->name('admin.roles.create');
    Route::get('roles/edit/{id}', 'IndexController@getEdit')->name('admin.roles.edit');
    Route::post('roles/edit/{id}', 'IndexController@postEdit')->name('admin.roles.edit');
    Route::get('roles/delete/{id}', 'IndexController@getDelete')->name('admin.roles.delete');
    Route::post('roles/deleteAll', 'IndexController@getDeleteAll')->name('admin.roles.deleteAll');
    Route::get('roles/export/{fileType}', 'IndexController@getExport')->name('admin.roles.export');
});
