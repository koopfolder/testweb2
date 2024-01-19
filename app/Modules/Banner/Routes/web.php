<?php

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
        Route::get('banner', 'IndexController@getIndex')->name('admin.banner.index');
        Route::get('banner/create', 'IndexController@getCreate')->name('admin.banner.create');
        Route::post('banner/create', 'IndexController@postCreate')->name('admin.banner.create');
        Route::get('banner/edit/{id}', 'IndexController@getEdit')->name('admin.banner.edit');
        Route::post('banner/edit/{id}', 'IndexController@postEdit')->name('admin.banner.edit');
        Route::get('banner/delete/{id}', 'IndexController@getDelete')->name('admin.banner.delete');
        Route::post('banner/deleteAll', 'IndexController@postDeleteAll')->name('admin.banner.deleteAll');

        // categories
        Route::get('banner/category', 'CategoriesController@getIndex')->name('admin.banner.category.index');
        Route::get('banner/category/create', 'CategoriesController@getCreate')->name('admin.banner.category.create');
        Route::post('banner/category/create', 'CategoriesController@postCreate')->name('admin.banner.category.create');
        Route::get('banner/category/edit/{id}', 'CategoriesController@getEdit')->name('admin.banner.category.edit');
        Route::post('banner/category/edit/{id}', 'CategoriesController@postEdit')->name('admin.banner.category.edit');
        Route::get('banner/category/delete/{id}', 'CategoriesController@getDelete')->name('admin.banner.category.delete');
        Route::post('banner/category/deleteAll', 'CategoriesController@postDeleteAll')->name('admin.banner.category.deleteAll');

        // revision
        Route::get('banner/reverse/{id}', 'IndexController@getReverse')->name('admin.banner.reverse');
        Route::get('banner/category/reverse/{id}', 'CategoriesController@getReverse')->name('admin.banner.category.reverse');
    });
});

