<?php

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('thing', 'IndexController@getIndex')->name('admin.thing.index');
    Route::get('thing/create', 'IndexController@getCreate')->name('admin.thing.create');
    Route::post('thing/create', 'IndexController@postCreate')->name('admin.thing.create');
    Route::get('thing/edit/{id}', 'IndexController@getEdit')->name('admin.thing.edit');
    Route::post('thing/edit/{id}', 'IndexController@postEdit')->name('admin.thing.edit');
    Route::get('thing/delete/{id}', 'IndexController@getDelete')->name('admin.thing.delete');
    Route::post('thing/deleteAll', 'IndexController@postDeleteAll')->name('admin.thing.deleteAll');
    Route::get('thing/reverse/{id}', 'IndexController@getReverse')->name('admin.thing.reverse');
});
