<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('business', 'BusinessController@getIndex')->name('admin.business.index');
    Route::post('business/store', 'BusinessController@postStore')->name('admin.business.store');
    Route::get('business/reverse/{id}', 'BusinessController@getReverse')->name('admin.business.reverse');
});


Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function(){
    Route::get('preview/business','FrontController@getPreview')->name('preview-business');
});
