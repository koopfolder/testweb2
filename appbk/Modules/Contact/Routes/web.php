<?php

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{

	Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
	    Route::get('contact', 'IndexController@getIndex')->name('admin.contact.index');
	    Route::get('contact/delete/{id}', 'IndexController@getDelete')->name('admin.contact.delete');
	    Route::post('contact/deleteAll', 'IndexController@postDeleteAll')->name('admin.contact.deleteAll');


	    Route::get('contact/subject', 'SubjectController@getIndex')->name('admin.contact.subject.index');
	    Route::get('contact/subject/create', 'SubjectController@getCreate')->name('admin.contact.subject.create');
    	Route::post('contact/subject/create', 'SubjectController@postCreate')->name('admin.contact.subject.create');
	    Route::get('contact/subject/edit/{id}', 'SubjectController@getEdit')->name('admin.contact.subject.edit');
    	Route::post('contact/subject/edit/{id}', 'SubjectController@postEdit')->name('admin.contact.subject.edit');
    	Route::get('contact/subject/delete/{id}', 'SubjectController@getDelete')->name('admin.contact.subject.delete');
   		Route::post('contact/subject/deleteAll', 'SubjectController@postDeleteAll')->name('admin.contact.subject.deleteAll');
	});
});
Route::group([], function()
{
	//dd("Post Contact");
	//Route::post('contact', 'FrontController@postContact')->name('contact.form');
	Route::post('contact/save', 'FrontController@postContact')->name('contact.form');
});

//Route::post('contact', 'FrontController@postContact')->name('contact.form');