<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('users', 'IndexController@getIndex')->name('admin.users.index');
    Route::get('users/setting', 'IndexController@getSetting')->name('admin.users.setting');
    Route::post('users/setting', 'IndexController@postSetting')->name('admin.users.setting');
    Route::post('users/setting/deleteAll', 'IndexController@postSettingDeleteAll')->name('admin.users.setting.deleteAll');

    Route::get('users/create', 'IndexController@getCreate')->name('admin.users.create');
    Route::post('users/create', 'IndexController@postCreate')->name('admin.users.create');
    Route::get('users/edit/{id}', 'IndexController@getEdit')->name('admin.users.edit');
    Route::post('users/edit/{id}', 'IndexController@postEdit')->name('admin.users.edit');
    Route::get('users/delete/{id}', 'IndexController@getDelete')->name('admin.users.delete');
    Route::post('users/deleteAll', 'IndexController@postDeleteAll')->name('admin.users.deleteAll');
    Route::get('users/deleteImage/{id}', 'IndexController@getDeleteImage')->name('admin.users.deleteImage');
    Route::get('users/export/{fileType}', 'IndexController@getExport')->name('admin.users.export');
    Route::get('connectionauthority', 'ConnectionauthorityController@getIndex')->name('admin.connectionauthority.index');

    Route::get('connectionauthority/create', 'ConnectionauthorityController@getCreate')->name('admin.connectionauthority.create');
    Route::post('connectionauthority/create', 'ConnectionauthorityController@postCreate')->name('admin.connectionauthority.create');
    Route::get('connectionauthority/edit/{id}', 'ConnectionauthorityController@getEdit')->name('admin.connectionauthority.edit');
    Route::post('connectionauthority/edit/{id}', 'ConnectionauthorityController@postEdit')->name('admin.connectionauthority.edit');
    Route::get('connectionauthority/delete/{id}', 'ConnectionauthorityController@getDelete')->name('admin.connectionauthority.delete');
    Route::post('connectionauthority/deleteAll', 'ConnectionauthorityController@postDeleteAll')->name('admin.connectionauthority.deleteAll');
});

Route::group([], function()
{
    Route::post('register/create', 'FrontendController@postCreate')->name('register.create');
    Route::post('user/forgotpassword', 'FrontendController@postForgotpassword')->name('user.forgotpassword');
    Route::get('user/activate/{token}', 'FrontendController@getActivate')->name('user.activate');
    Route::get('user/reset/{token}', 'FrontendController@getReset')->name('user.getreset');
    Route::get('user/resend-email', 'FrontendController@getResendEmail')->name('user.getresend.email');
    Route::post('user/reset', 'FrontendController@postReset')->name('user.reset');
    //Route::post('register/checkemail', 'FrontendController@postCheckEmail')->name('register.checkemail');
});
