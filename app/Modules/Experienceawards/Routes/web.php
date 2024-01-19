<?php

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {

    Route::get('experienceawards/', 'IndexController@getIndex')->name('admin.experienceawards.index');
    Route::get('experienceawards/create', 'IndexController@getCreate')->name('admin.experienceawards.create');
    Route::post('experienceawards/create', 'IndexController@postCreate')->name('admin.experienceawards.create');
    Route::get('experienceawards/edit/{id}', 'IndexController@getEdit')->name('admin.experienceawards.edit');
    Route::post('experienceawards/edit/{id}', 'IndexController@postEdit')->name('admin.experienceawards.edit');
    Route::get('experienceawards/delete/{id}', 'IndexController@getDelete')->name('admin.experienceawards.delete');
    Route::post('experienceawards/deleteAll', 'IndexController@postDeleteAll')->name('admin.experienceawards.deleteAll');
    Route::get('experienceawards/reverse/{id}', 'IndexController@getReverse')->name('admin.experienceawards.reverse');
    Route::post('experienceawards/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.experienceawards.ajaxdeletegallery');
    
    Route::get('experience/awards', 'AwardController@getIndex')->name('admin.experience.awards.index');
    Route::get('experience/awards/create', 'AwardController@getCreate')->name('admin.experience.awards.create');
    Route::post('experience/awards/create', 'AwardController@postCreate')->name('admin.experience.awards.create');
    Route::get('experienceawards/awards/edit/{id}', 'AwardController@getEdit')->name('admin.experience.awards.edit');
    Route::post('experienceawards/awards/edit/{id}', 'AwardController@postEdit')->name('admin.experience.awards.edit');
    Route::get('experienceawards/awards/delete/{id}', 'AwardController@getDelete')->name('admin.experience.awards.delete');
    Route::post('experienceawards/awards/deleteAll', 'AwardController@postDeleteAll')->name('admin.experience.awards.deleteAll');
    Route::get('experienceawards/awards/reverse/{id}', 'AwardController@getReverse')->name('admin.experience.awards.reverse');
    Route::post('experienceawards/awards/delete-gallery', 'AwardController@postAjaxDeleteGallery')->name('admin.experience.awards.ajaxdeletegallery');
     Route::post('experienceawards/AjaxUpdateOrder', 'IndexController@postUpdateOrder')->name('admin.experienceawards.AjaxUpdateOrder');

});



Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/experience-awards','FrontController@getPreview')->name('preview-experience-awards');
});
