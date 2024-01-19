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

    Route::get('product', 'IndexController@getIndex')->name('admin.product.index');
    Route::get('product/categories', 'IndexController@getCategories')->name('admin.product.categories.index');
    Route::get('product/categories/create', 'IndexController@getCategoriesCreate')->name('admin.product.categories.create');
    Route::post('product/categories/create', 'IndexController@postCategoriesCreate')->name('admin.product.categories.create');
    Route::get('product/categories/edit/{id}', 'IndexController@getCategoriesEdit')->name('admin.product.categories.edit');
    Route::post('product/categories/edit/{id}', 'IndexController@postCategoriesEdit')->name('admin.product.categories.edit');
    Route::get('product/categories/delete/{id}', 'IndexController@getCategoriesDelete')->name('admin.product.categories.delete');
    Route::get('product/categories/export/{fileType}', 'IndexController@getExportCategory')->name('admin.product.categories.export');

    Route::get('product/create', 'IndexController@getCreate')->name('admin.product.create');
    Route::post('product/create', 'IndexController@postCreate')->name('admin.product.create');
    Route::get('product/edit/{id}', 'IndexController@getEdit')->name('admin.product.edit');
    Route::post('product/edit/{id}', 'IndexController@postEdit')->name('admin.product.edit');
    Route::get('product/delete/{id}', 'IndexController@getDelete')->name('admin.product.delete');
    Route::post('product/deleteAll', 'IndexController@postDeleteAll')->name('admin.product.deleteAll');
    Route::get('product/export/{fileType}', 'IndexController@getExport')->name('admin.product.export');


    Route::get('product/publish/{id}/{status}', 'IndexController@getPublish')->name('admin.product.publish.index');

    Route::get('product/revision/{id}', 'IndexController@getRevision')->name('admin.product.revision');
    // 
    // Route::get('product/revision', 'IndexController@getRevision')->name('admin.product.revision.index');
    // Route::get('product/revision/review/{id}', 'IndexController@getReview')->name('admin.product.revision.review');
    // Route::post('product/revision/review/{id}', 'IndexController@postReview')->name('admin.product.revision.review');
    // Route::get('product/revision/deleteRevision/{id}', 'IndexController@getDeleteRevision')->name('admin.product.revision.deleteRevision');
    // Route::post('product/revision/deleteAllRevision', 'IndexController@postDeleteAllRevision')->name('admin.product.revision.deleteAllRevision');
    // Route::get('product/revision/export/{fileType}', 'IndexController@getExportRevision')->name('admin.product.revision.exportRevision');
});
