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
	Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
		
	    Route::get('restaurant', 'IndexController@getIndex')->name('admin.restaurant.index');
	    Route::get('restaurant/categories', 'IndexController@getCategories')->name('admin.restaurant.categories.index');
	    Route::get('restaurant/categories/create', 'IndexController@getCategoriesCreate')->name('admin.restaurant.categories.create');
	    Route::post('restaurant/categories/create', 'IndexController@postCategoriesCreate')->name('admin.restaurant.categories.create');
	    Route::get('restaurant/categories/edit/{id}', 'IndexController@getCategoriesEdit')->name('admin.restaurant.categories.edit');
	    Route::post('restaurant/categories/edit/{id}', 'IndexController@postCategoriesEdit')->name('admin.restaurant.categories.edit');
	    Route::get('restaurant/categories/delete/{id}', 'IndexController@getCategoriesDelete')->name('admin.restaurant.categories.delete');
	    Route::get('restaurant/categories/export/{fileType}', 'IndexController@getExportCategory')->name('admin.restaurant.categories.export');

	    Route::get('restaurant/create', 'IndexController@getCreate')->name('admin.restaurant.create');
	    Route::post('restaurant/create', 'IndexController@postCreate')->name('admin.restaurant.create');
	    Route::get('restaurant/edit/{id}', 'IndexController@getEdit')->name('admin.restaurant.edit');
	    Route::post('restaurant/edit/{id}', 'IndexController@postEdit')->name('admin.restaurant.edit');
	    Route::get('restaurant/delete/{id}', 'IndexController@getDelete')->name('admin.restaurant.delete');
	    Route::post('restaurant/deleteAll', 'IndexController@postDeleteAll')->name('admin.restaurant.deleteAll');
	    Route::get('restaurant/export/{fileType}', 'IndexController@getExport')->name('admin.restaurant.export');


	    Route::get('restaurant/publish/{id}/{status}', 'IndexController@getPublish')->name('admin.restaurant.publish.index');

		Route::get('restaurant/revision/{id}', 'IndexController@getRevision')->name('admin.restaurant.revision');
		Route::get('restaurant/reverse/{id}', 'IndexController@getReverse')->name('admin.restaurant.reverse');

	    // 
	    // Route::get('product/revision', 'IndexController@getRevision')->name('admin.product.revision.index');
	    // Route::get('product/revision/review/{id}', 'IndexController@getReview')->name('admin.product.revision.review');
	    // Route::post('product/revision/review/{id}', 'IndexController@postReview')->name('admin.product.revision.review');
	    // Route::get('product/revision/deleteRevision/{id}', 'IndexController@getDeleteRevision')->name('admin.product.revision.deleteRevision');
	    // Route::post('product/revision/deleteAllRevision', 'IndexController@postDeleteAllRevision')->name('admin.product.revision.deleteAllRevision');
	    // Route::get('product/revision/export/{fileType}', 'IndexController@getExportRevision')->name('admin.product.revision.exportRevision');
	});

});
