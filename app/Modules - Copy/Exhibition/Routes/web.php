<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function (){


    /*Exhibition Master*/
    Route::get('exhibition/master/', 'ExhibitionMasterController@getIndex')->name('admin.exhibition.master.index');
    Route::get('exhibition/master/create', 'ExhibitionMasterController@getCreate')->name('admin.exhibition.master.create');
    Route::post('exhibition/master/create', 'ExhibitionMasterController@postCreate')->name('admin.exhibition.master.create');
    Route::get('exhibition/master/edit/master/{id}', 'ExhibitionMasterController@getEdit')->name('admin.exhibition.master.edit');
    Route::post('exhibition/master/edit/master/{id}', 'ExhibitionMasterController@postEdit')->name('admin.exhibition.master.edit');
    Route::get('exhibition/master/delete/master/{id}', 'ExhibitionMasterController@getDelete')->name('admin.exhibition.master.delete');
    Route::post('exhibition/master/deleteAll', 'ExhibitionMasterController@postDeleteAll')->name('admin.exhibition.master.deleteAll');
    Route::get('exhibition-master-iframe', 'ExhibitionMasterController@getIndexiframe')->name('admin.exhibition.master.index_iframe');

    /*Exhibition */
    Route::get('exhibition/', 'ExhibitionController@getIndex')->name('admin.exhibition.index');
    Route::get('exhibition/create', 'ExhibitionController@getCreate')->name('admin.exhibition.create');
    Route::post('exhibition/create', 'ExhibitionController@postCreate')->name('admin.exhibition.create');
    Route::get('exhibition/edit/{id}', 'ExhibitionController@getEdit')->name('admin.exhibition.edit');
    Route::post('exhibition/edit/{id}', 'ExhibitionController@postEdit')->name('admin.exhibition.edit');
    Route::get('exhibition/delete/{id}', 'ExhibitionController@getDelete')->name('admin.exhibition.delete');
    Route::post('exhibition/deleteAll', 'ExhibitionController@postDeleteAll')->name('admin.exhibition.deleteAll');
    Route::get('exhibition-iframe', 'ExhibitionController@getIndexiframe')->name('admin.exhibition.index_iframe');


    /*Exhibition Online*/
    Route::get('exhibition/online/', 'ExhibitiononlineController@getIndex')->name('admin.exhibition.online.index');
    Route::get('exhibition/online/create', 'ExhibitiononlineController@getCreate')->name('admin.exhibition.online.create');
    Route::post('exhibition/online/create', 'ExhibitiononlineController@postCreate')->name('admin.exhibition.online.create');
    Route::get('exhibition/online/edit/online/{id}', 'ExhibitiononlineController@getEdit')->name('admin.exhibition.online.edit');
    Route::post('exhibition/online/edit/online/{id}', 'ExhibitiononlineController@postEdit')->name('admin.exhibition.online.edit');
    Route::get('exhibition/online/delete/online/{id}', 'ExhibitiononlineController@getDelete')->name('admin.exhibition.online.delete');
    Route::post('exhibition/online/deleteAll', 'ExhibitiononlineController@postDeleteAll')->name('admin.exhibition.online.deleteAll');
    Route::get('exhibition-online-iframe', 'ExhibitiononlineController@getIndexiframe')->name('admin.exhibition.online.index_iframe');

    /*Revolving Exhibition*/
    Route::get('exhibition/revolving/', 'RevolvingController@getIndex')->name('admin.exhibition.revolving.index');
    Route::get('exhibition/revolving/create', 'RevolvingController@getCreate')->name('admin.exhibition.revolving.create');
    Route::post('exhibition/revolving/create', 'RevolvingController@postCreate')->name('admin.exhibition.revolving.create');
    Route::get('exhibition/revolving/edit/online/{id}', 'RevolvingController@getEdit')->name('admin.exhibition.revolving.edit');
    Route::post('exhibition/revolving/edit/online/{id}', 'RevolvingController@postEdit')->name('admin.exhibition.revolving.edit');
    Route::get('exhibition/revolving/delete/online/{id}', 'RevolvingController@getDelete')->name('admin.exhibition.revolving.delete');
    Route::post('exhibition/revolving/deleteAll', 'RevolvingController@postDeleteAll')->name('admin.exhibition.revolving.deleteAll');
    Route::get('exhibition/revolving/reverse/{id}', 'RevolvingController@getReverse')->name('admin.exhibition.revolving.reverse');
    Route::post('exhibition/revolving/delete-gallery', 'RevolvingController@postAjaxDeleteGallery')->name('admin.exhibition.revolving.ajaxdeletegallery');
    Route::get('exhibition-revolving-iframe', 'RevolvingController@getIndexiframe')->name('admin.exhibition.revolving.index_iframe');
    
    /*Permanent Exhibition*/
    Route::get('exhibition/permanent/', 'PermanentController@getIndex')->name('admin.exhibition.permanent.index');
    Route::get('exhibition/permanent/create', 'PermanentController@getCreate')->name('admin.exhibition.permanent.create');
    Route::post('exhibition/permanent/create', 'PermanentController@postCreate')->name('admin.exhibition.permanent.create');
    Route::get('exhibition/permanent/edit/online/{id}', 'PermanentController@getEdit')->name('admin.exhibition.permanent.edit');
    Route::post('exhibition/permanent/edit/online/{id}', 'PermanentController@postEdit')->name('admin.exhibition.permanent.edit');
    Route::get('exhibition/permanent/delete/online/{id}', 'PermanentController@getDelete')->name('admin.exhibition.permanent.delete');
    Route::post('exhibition/permanent/deleteAll', 'PermanentController@postDeleteAll')->name('admin.exhibition.permanent.deleteAll');
    Route::get('exhibition/permanent/reverse/{id}', 'PermanentController@getReverse')->name('admin.exhibition.permanent.reverse');
    Route::post('exhibition/permanent/delete-gallery', 'PermanentController@postAjaxDeleteGallery')->name('admin.exhibition.permanent.ajaxdeletegallery');
    Route::get('exhibition-permanent-iframe', 'PermanentController@getIndexiframe')->name('admin.exhibition.permanent.index_iframe');
    

    /*Traveling Exhibition*/
    Route::get('exhibition/traveling/', 'TravelingController@getIndex')->name('admin.exhibition.traveling.index');
    Route::get('exhibition/traveling/create', 'TravelingController@getCreate')->name('admin.exhibition.traveling.create');
    Route::post('exhibition/traveling/create', 'TravelingController@postCreate')->name('admin.exhibition.traveling.create');
    Route::get('exhibition/traveling/edit/online/{id}', 'TravelingController@getEdit')->name('admin.exhibition.traveling.edit');
    Route::post('exhibition/traveling/edit/online/{id}', 'TravelingController@postEdit')->name('admin.exhibition.traveling.edit');
    Route::get('exhibition/traveling/delete/online/{id}', 'TravelingController@getDelete')->name('admin.exhibition.traveling.delete');
    Route::post('exhibition/traveling/deleteAll', 'TravelingController@postDeleteAll')->name('admin.exhibition.traveling.deleteAll');
    Route::get('exhibition/traveling/reverse/{id}', 'TravelingController@getReverse')->name('admin.exhibition.traveling.reverse');
    Route::post('exhibition/traveling/delete-gallery', 'TravelingController@postAjaxDeleteGallery')->name('admin.exhibition.traveling.ajaxdeletegallery');
    Route::get('exhibition-traveling-iframe', 'TravelingController@getIndexiframe')->name('admin.exhibition.traveling.index_iframe');
    
   /*Exhibition Borrowed*/
   Route::get('exhibition/borrowed/', 'BorrowedController@getIndex')->name('admin.exhibition.borrowed.index');
   Route::get('exhibition/borrowed/create', 'BorrowedController@getCreate')->name('admin.exhibition.borrowed.create');
   Route::post('exhibition/borrowed/create', 'BorrowedController@postCreate')->name('admin.exhibition.borrowed.create');
   Route::get('exhibition/borrowed/edit/online/{id}', 'BorrowedController@getEdit')->name('admin.exhibition.borrowed.edit');
   Route::post('exhibition/borrowed/edit/online/{id}', 'BorrowedController@postEdit')->name('admin.exhibition.borrowed.edit');
   Route::get('exhibition/borrowed/delete/online/{id}', 'BorrowedController@getDelete')->name('admin.exhibition.borrowed.delete');
   Route::post('exhibition/borrowed/deleteAll', 'BorrowedController@postDeleteAll')->name('admin.exhibition.borrowed.deleteAll');
   Route::get('exhibition/borrowed/reverse/{id}', 'BorrowedController@getReverse')->name('admin.exhibition.borrowed.reverse');
   Route::post('exhibition/borrowed/delete-gallery', 'BorrowedController@postAjaxDeleteGallery')->name('admin.exhibition.borrowed.ajaxdeletegallery');
   Route::get('exhibition-borrowed-iframe', 'BorrowedController@getIndexiframe')->name('admin.exhibition.borrowed.index_iframe');

   /*Book An Exhibition*/
   Route::get('book-an-exhibition', 'BookAnExhibitionController@getIndex')->name('admin.book-an-exhibition.index');


});

//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::group([], function(){
    
    // Route::get('revolving-exhibition/{slug}','FrontendController@getDetail')->name('revolving-exhibition-detail');
    // Route::get('permanent-exhibition/{slug}','FrontendController@getDetail')->name('permanent-exhibition-detail');
    // Route::get('traveling-exhibition/{slug}','FrontendController@getDetail')->name('traveling-exhibition-detail');
    // Route::get('online-exhibition/{slug}','FrontendController@getDetail')->name('online-exhibition-detail');
    // Route::get('exhibition-borrowed/{slug}','FrontendController@getDetail')->name('exhibition-borrowed-detail');
    Route::get('exhibition/{slug}','FrontendController@getDetail')->name('exhibition-detail');
    //Route::get('exhibition/{slug}','FrontendController@getDetail')->name('exhibition-detail');
    Route::get('book-an-exhibition/{id}','FrontendController@getBookAnExhibitionFront')->name('book-an-exhibition');
    Route::post('book-an-exhibition/front/create','FrontendController@postFrontCreate')->name('book-an-exhibition.create');
    
});






