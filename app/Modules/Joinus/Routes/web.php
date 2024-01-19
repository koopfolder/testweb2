<?php

/*Backend*/
Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function () {
    Route::get('candidate', 'IndexController@getIndex')->name('admin.career.index');
    Route::get('candidate/viewmore/{id}', 'IndexController@getViewmore')->name('admin.viewmore.index');

    Route::get('candidate/position', 'PositionController@getIndex')->name('admin.position.index');
    Route::get('candidate/position/create', 'PositionController@getCreate')->name('admin.position.create');
    Route::post('candidate/position/create', 'PositionController@postCreate')->name('admin.position.create');
    Route::get('candidate/position/edit/{id}', 'PositionController@getEdit')->name('admin.position.edit');
    Route::post('candidate/position/edit/{id}', 'PositionController@postEdit')->name('admin.position.edit');
    Route::get('candidate/position/delete/{id}', 'PositionController@getDelete')->name('admin.position.delete');
    Route::post('candidate/position/deleteAll', 'PositionController@postDeleteAll')->name('admin.position.deleteAll');
    Route::get('candidate/position/reverse/{id}', 'PositionController@getReverse')->name('admin.position.reverse');
});

