<?php

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	Route::group(['prefix' => 'admin'], function () 
	{
	    Route::get('export/{moduleSlug}/{table}', 'IndexController@getIndex')->name('admin.export.index');
	});
});

