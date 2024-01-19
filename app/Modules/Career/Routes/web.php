<?php

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
	    Route::get('career', 'IndexController@getIndex')->name('admin.career.index');
	    Route::get('career/delete/{id}', 'IndexController@getDelete')->name('admin.career.delete');
	    Route::post('career/deleteAll', 'IndexController@postDeleteAll')->name('admin.career.deleteAll');
	});

	Route::post('upload-cv', 'FrontController@postUpload')->name('home.upload.career');

});


