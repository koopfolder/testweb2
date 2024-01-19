<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('chairmanstatement/', 'IndexController@getIndex')->name('admin.chairmanstatement.index');
    Route::get('chairmanstatement/create', 'IndexController@getCreate')->name('admin.chairmanstatement.create');
    Route::post('chairmanstatement/create', 'IndexController@postCreate')->name('admin.chairmanstatement.create');
    Route::get('chairmanstatement/edit/{id}', 'IndexController@getEdit')->name('admin.chairmanstatement.edit');
    Route::post('chairmanstatement/edit/{id}', 'IndexController@postEdit')->name('admin.chairmanstatement.edit');
    Route::get('chairmanstatement/delete/{id}', 'IndexController@getDelete')->name('admin.chairmanstatement.delete');
    Route::post('chairmanstatement/deleteAll', 'IndexController@postDeleteAll')->name('admin.chairmanstatement.deleteAll');
    Route::get('chairmanstatement/reverse/{id}', 'IndexController@getReverse')->name('admin.chairmanstatement.reverse');
    Route::post('chairmanstatement/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.chairmanstatement.ajaxdeletegallery');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function(){
    Route::get('preview/chairman-statement','FrontController@getPreview')->name('preview-chairman-statement');
});
