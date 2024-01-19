<?php

Route::group(['prefix' => 'admin', 'middleware' => 'menus'], function () {
    Route::get('organization', 'IndexController@getCreate')->name('admin.organization.index');
    Route::post('organization/create', 'IndexController@postCreate')->name('admin.organization.create');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/organization-chart','FrontController@getPreview')->name('preview-organization-chart');
});

