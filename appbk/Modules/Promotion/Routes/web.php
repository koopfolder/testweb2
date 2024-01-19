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
	    Route::get('promotion', 'IndexController@getIndex')->name('admin.promotion.index');
	    Route::get('promotion/categories', 'IndexController@getCategories')->name('admin.promotion.categories.index');
	    Route::get('promotion/categories/create', 'IndexController@getCategoriesCreate')->name('admin.promotion.categories.create');
	    Route::post('promotion/categories/create', 'IndexController@postCategoriesCreate')->name('admin.promotion.categories.create');
	    Route::get('promotion/categories/edit/{id}', 'IndexController@getCategoriesEdit')->name('admin.promotion.categories.edit');
	    Route::post('promotion/categories/edit/{id}', 'IndexController@postCategoriesEdit')->name('admin.promotion.categories.edit');
	    Route::get('promotion/categories/delete/{id}', 'IndexController@getCategoriesDelete')->name('admin.promotion.categories.delete');
	    Route::get('promotion/categories/export/{fileType}', 'IndexController@getExportCategory')->name('admin.promotion.categories.export');

	    Route::get('promotion/create', 'IndexController@getCreate')->name('admin.promotion.create');
	    Route::post('promotion/create', 'IndexController@postCreate')->name('admin.promotion.create');
	    Route::get('promotion/edit/{id}', 'IndexController@getEdit')->name('admin.promotion.edit');
	    Route::post('promotion/edit/{id}', 'IndexController@postEdit')->name('admin.promotion.edit');
	    Route::get('promotion/delete/{id}', 'IndexController@getDelete')->name('admin.promotion.delete');
	    Route::post('promotion/deleteAll', 'IndexController@postDeleteAll')->name('admin.promotion.deleteAll');
	    Route::get('promotion/export/{fileType}', 'IndexController@getExport')->name('admin.promotion.export');
		Route::get('promotion/publish/{id}/{status}', 'IndexController@getPublish')->name('admin.promotion.publish.index');
		Route::get('promotion/reverse/{id}', 'IndexController@getReverse')->name('admin.promotion.reverse');
	});
});
