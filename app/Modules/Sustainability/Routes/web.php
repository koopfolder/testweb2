<?php

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('sustainability/corporate-gov-policy', 'CorporategovpolicyController@getIndex')
    			->name('admin.sustainability.corporate-gov-policy.index');
    Route::get('sustainability/corporate-gov-policy/create', 'CorporategovpolicyController@getCreate')
    			->name('admin.sustainability.corporate-gov-policy.create');
    Route::post('sustainability/corporate-gov-policy/create', 'CorporategovpolicyController@postCreate')
    			->name('admin.sustainability.corporate-gov-policy.create');
    Route::get('sustainability/corporate-gov-policy/edit/{id}', 'CorporategovpolicyController@getEdit')
    			->name('admin.sustainability.corporate-gov-policy.edit');
    Route::post('sustainability/corporate-gov-policy/edit/{id}', 'CorporategovpolicyController@postEdit')
    			->name('admin.sustainability.corporate-gov-policy.edit');
    Route::get('sustainability/corporate-gov-policy/delete/{id}', 'CorporategovpolicyController@getDelete')
    			->name('admin.sustainability.corporate-gov-policy.delete');
    Route::post('sustainability/corporate-gov-policy/deleteAll', 'CorporategovpolicyController@postDeleteAll')
    			->name('admin.sustainability.corporate-gov-policy.deleteAll');
    Route::get('sustainability/corporate-gov-policy/reverse/{id}', 'CorporategovpolicyController@getReverse')
    			->name('admin.sustainability.corporate-gov-policy.reverse');
    Route::post('sustainability/corporate-gov-policy/delete-gallery', 'CorporategovpolicyController@postAjaxDeleteGallery')
    			->name('admin.sustainability.corporate-gov-policy.ajaxdeletegallery');
});


Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/corporate-governance-policy','FrontController@getPreview')->name('preview-corporate-governance-policy');
});

