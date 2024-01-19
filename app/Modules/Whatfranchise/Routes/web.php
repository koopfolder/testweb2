<?php

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('whatfranchise', 'IndexController@getIndex')->name('admin.whatfranchise.index');
    Route::get('whatfranchise/create', 'IndexController@getCreate')->name('admin.whatfranchise.create');
    Route::post('whatfranchise/create', 'IndexController@postCreate')->name('admin.whatfranchise.create');
    Route::get('whatfranchise/edit/{id}', 'IndexController@getEdit')->name('admin.whatfranchise.edit');
    Route::post('whatfranchise/edit/{id}', 'IndexController@postEdit')->name('admin.whatfranchise.edit');
    Route::get('whatfranchise/delete/{id}', 'IndexController@getDelete')->name('admin.whatfranchise.delete');
    Route::post('whatfranchise/deleteAll', 'IndexController@postDeleteAll')->name('admin.whatfranchise.deleteAll');
    Route::get('whatfranchise/reverse/{id}', 'IndexController@getReverse')->name('admin.whatfranchise.reverse');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/whatfranchise','FrontController@getPreview')->name('preview-whatfranchise-chart');
});

