<?php

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('room', 'IndexController@getIndex')->name('admin.room.index');
    Route::get('room/create', 'IndexController@getCreate')->name('admin.room.create');
    Route::post('room/create', 'IndexController@postCreate')->name('admin.room.create');
    Route::get('room/edit/{id}', 'IndexController@getEdit')->name('admin.room.edit');
    Route::post('room/edit/{id}', 'IndexController@postEdit')->name('admin.room.edit');
    Route::get('room/delete/{id}', 'IndexController@getDelete')->name('admin.room.delete');
    Route::post('room/deleteAll', 'IndexController@postDeleteAll')->name('admin.room.deleteAll');

    Route::get('room/category', 'CategoriesController@getIndex')->name('admin.room.category.index');
    Route::get('room/category/create', 'CategoriesController@getCreate')->name('admin.room.category.create');
    Route::post('room/category/create', 'CategoriesController@postCreate')->name('admin.room.category.create');
    Route::get('room/category/edit/{id}', 'CategoriesController@getEdit')->name('admin.room.category.edit');
    Route::post('room/category/edit/{id}', 'CategoriesController@postEdit')->name('admin.room.category.edit');
    Route::get('room/category/delete/{id}', 'CategoriesController@getDelete')->name('admin.room.category.delete');
    Route::post('room/category/deleteAll', 'CategoriesController@postDeleteAll')->name('admin.room.category.deleteAll');

    Route::get('room/reverse/{id}', 'IndexController@getReverse')->name('admin.room.reverse');
    Route::get('room/category/reverse/{id}', 'CategoriesController@getReverse')->name('admin.room.category.reverse');
});



Route::post('booking', 'BookingController@getPrepareUrl')->name('admin.room.prepare.url');
