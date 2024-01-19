<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('csrpolicy/', 'IndexController@getIndex')->name('admin.csrpolicy.index');
    Route::get('csrpolicy/create', 'IndexController@getCreate')->name('admin.csrpolicy.create');
    Route::post('csrpolicy/create', 'IndexController@postCreate')->name('admin.csrpolicy.create');
    Route::get('csrpolicy/edit/{id}', 'IndexController@getEdit')->name('admin.csrpolicy.edit');
    Route::post('csrpolicy/edit/{id}', 'IndexController@postEdit')->name('admin.csrpolicy.edit');
    Route::get('csrpolicy/delete/{id}', 'IndexController@getDelete')->name('admin.csrpolicy.delete');
    Route::post('csrpolicy/deleteAll', 'IndexController@postDeleteAll')->name('admin.csrpolicy.deleteAll');
    Route::get('csrpolicy/reverse/{id}', 'IndexController@getReverse')->name('admin.csrpolicy.reverse');
    Route::post('csrpolicy/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.csrpolicy.ajaxdeletegallery');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/csr-policy','FrontController@getPreview')->name('preview-csr-policy');
});
