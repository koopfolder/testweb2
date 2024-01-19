<?php
Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {

    Route::get('franchise/', 'IndexController@getIndex')->name('admin.franchise.index');
    Route::get('franchise/create', 'IndexController@getCreate')->name('admin.franchise.create');
    Route::post('franchise/create', 'IndexController@postCreate')->name('admin.franchise.create');
    Route::get('franchise/edit/{id}', 'IndexController@getEdit')->name('admin.franchise.edit');
    Route::post('franchise/edit/{id}', 'IndexController@postEdit')->name('admin.franchise.edit');
    Route::get('franchise/delete/{id}', 'IndexController@getDelete')->name('admin.franchise.delete');
    Route::post('franchise/deleteAll', 'IndexController@postDeleteAll')->name('admin.franchise.deleteAll');
    Route::get('franchise/reverse/{id}', 'IndexController@getReverse')->name('admin.franchise.reverse');
    Route::post('franchise/delete-gallery', 'IndexController@postAjaxDeleteGallery')->name('admin.franchise.ajaxdeletegallery');
    Route::post('franchise/delete-document', 'IndexController@postAjaxDeleteDocument')->name('admin.franchise.ajaxdeletedocument');

    Route::get('franchise/category', 'CategoryController@getIndex')->name('admin.franchise.category.index');
    Route::get('franchise/category/create', 'CategoryController@getCreate')->name('admin.franchise.category.create');
    Route::post('franchise/category/create', 'CategoryController@postCreate')->name('admin.franchise.category.create');
    Route::get('franchise/category/edit/{id}', 'CategoryController@getEdit')->name('admin.franchise.category.edit');
    Route::post('franchise/category/edit/{id}', 'CategoryController@postEdit')->name('admin.franchise.category.edit');
    Route::get('franchise/category/delete/{id}', 'CategoryController@getDelete')->name('admin.franchise.category.delete');
    Route::post('franchise/category/deleteAll', 'CategoryController@postDeleteAll')->name('admin.franchise.category.deleteAll');
    Route::get('franchise/category/reverse/{id}', 'CategoryController@getReverse')->name('admin.franchise.category.reverse');
    Route::post('franchise/category/delete-gallery', 'CategoryController@postAjaxDeleteGallery')->name('admin.franchise.category.ajaxdeletegallery');

    Route::post('franchise/import', 'IndexController@postImport')->name('admin.franchise.import');

});


Route::group(['prefix'=>LaravelLocalization::setLocale(),'middleware'=>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/franchise/{slug}','FrontendController@getPreview')->name('preview-franchise');
    Route::get('แฟรนไชส์/รายการ/{category}','FrontendController@getListFranchiseCategory')->name('franchise-list-category');
    Route::get('แฟรนไชส์/รายการ/','FrontendController@getListFranchise')->name('franchise-list');
    Route::post('แฟรนไชส์/รายการ/','FrontendController@getListFranchise')->name('franchise-list');
    Route::get('แฟรนไชส์/{slug}','FrontendController@getDetailFranchise')->name('franchise-detail');
    
});