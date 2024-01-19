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

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	Route::group(['prefix' => 'admin'], function ()
	{
		Route::get('recreation', 'IndexController@getIndex')->name('admin.recreation.index');
	    Route::get('recreation/create', 'IndexController@getCreate')->name('admin.recreation.create');
	    Route::post('recreation/create', 'IndexController@postCreate')->name('admin.recreation.create');
	    Route::get('recreation/edit/{id}', 'IndexController@getEdit')->name('admin.recreation.edit');
	    Route::post('recreation/edit/{id}', 'IndexController@postEdit')->name('admin.recreation.edit');
	    Route::get('recreation/delete/{id}', 'IndexController@getDelete')->name('admin.recreation.delete');
		Route::post('recreation/deleteAll', 'IndexController@postDeleteAll')->name('admin.recreation.deleteAll');

		Route::get('recreation/reverse/{id}', 'IndexController@getReverse')->name('admin.recreation.reverse');
	});
});
