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

Route::group(['prefix' => 'admin'], function () {
    Route::get('revision', 'IndexController@getIndex')->name('admin.revision.index');
    Route::get('revision/review/{id}', 'IndexController@getReview')->name('admin.revision.review');
    Route::post('revision/review/{id}', 'IndexController@postReview')->name('admin.revision.review');
    Route::get('revision/delete/{id}', 'IndexController@getDelete')->name('admin.revision.delete');
    Route::post('revision/deleteAll', 'IndexController@postDeleteAll')->name('admin.revision.deleteAll');
    Route::get('revision/export/{fileType}', 'IndexController@getExport')->name('admin.revision.export');
});
