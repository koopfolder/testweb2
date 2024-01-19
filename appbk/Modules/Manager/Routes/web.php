<?php
/*Backend*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('manager', 'IndexController@getIndex')->name('admin.manager.index');
    Route::get('manager/create', 'IndexController@getCreate')->name('admin.manager.create');
    Route::post('manager/create', 'IndexController@postCreate')->name('admin.manager.create');
    Route::get('manager/edit/{id}', 'IndexController@getEdit')->name('admin.manager.edit');
    Route::post('manager/edit/{id}', 'IndexController@postEdit')->name('admin.manager.edit');
    Route::get('manager/delete/{id}', 'IndexController@getDelete')->name('admin.manager.delete');
    Route::post('manager/deleteAll', 'IndexController@postDeleteAll')->name('admin.manager.deleteAll');
    Route::get('manager/reverse/{id}', 'IndexController@getReverse')->name('admin.manager.reverse');
    Route::get('manager/categories','CategoriesController@getIndex')->name('admin.manager.categories.index');
    Route::get('manager/categories/create','CategoriesController@getCreate')->name('admin.manager.categories.create');
    Route::post('manager/categories/create','CategoriesController@postCreate')->name('admin.manager.categories.create');
    Route::get('manager/categories/edit/{id}','CategoriesController@getEdit')->name('admin.manager.categories.edit');
    Route::post('manager/categories/edit/{id}','CategoriesController@postEdit')->name('admin.manager.categories.edit');
    Route::get('manager/categories/delete/{id}', 'CategoriesController@getDelete')->name('admin.manager.categories.delete');
    Route::post('manager/categories/deleteAll', 'CategoriesController@postDeleteAll')->name('admin.manager.categories.deleteAll');
    Route::post('manager/categories/AjaxUpdateOrder', 'CategoriesController@postUpdateOrder')->name('admin.manager.categories.AjaxUpdateOrder');
    Route::post('manager/AjaxUpdateOrder', 'IndexController@postUpdateOrder')->name('admin.manager.AjaxUpdateOrder');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/board-of-director/detail/{id}','FrontController@getDetail')->name('preview-board-of-director-detail');
});
