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

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('profile', 'IndexController@getIndex')->name('admin.profile.index');
    Route::post('profile', 'IndexController@postIndex')->name('admin.profile.index');
    Route::get('delete-avatar/{id}', 'IndexController@getDeleteAvatar')->name('admin.profile.delete.avatar');
});


Route::group([], function()
{
    Route::post('profile/edit', 'IndexController@postFront')->name('profile.edit.index');
});