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
    Route::get('abilities', 'IndexController@getIndex')->name('admin.abilities.index');
    Route::get('abilities/create', 'IndexController@getCreate')->name('admin.abilities.create');
    Route::post('abilities/create', 'IndexController@postCreate')->name('admin.abilities.create');
    Route::get('abilities/edit/{id}', 'IndexController@getEdit')->name('admin.abilities.edit');
    Route::post('abilities/edit/{id}', 'IndexController@postEdit')->name('admin.abilities.edit');
    Route::get('abilities/delete/{id}', 'IndexController@getDelete')->name('admin.abilities.delete');
    Route::post('abilities/deleteAll', 'IndexController@getDeleteAll')->name('admin.abilities.deleteAll');
    Route::get('abilities/export/{fileType}', 'IndexController@getExport')->name('admin.abilities.export');
});
