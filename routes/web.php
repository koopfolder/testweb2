<?php


Route::get('thrc-token','HomeController@getToken')->name('thrc-token');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
    Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');
    Route::get('template/Interesting', 'HomeController@Interesting')->name('Interesting');
    // list all lfm routes here...
});
// 'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']


Route::group([], function()
{
    Route::post('seleted_for_you', 'HomeController@seleted_for_you')->name('seleted_for_you');
	/* ThaihealthWatch */ 
    Route::get('Thaihealthwatch',function(){
    	 return view('template.list_thaihealth_watch_case_front');
    })->name('list-thaihealth-watch');
    Route::get('thaihealthwatch',function(){
         return view('template.list_thaihealth_watch_case_front');
    })->name('list-thaihealth-watch2');

    /* learning_area_creates_direct_experience */ 
    Route::get('พื้นที่เรียนรู้สร้างประสบการณ์ตรง',function(){
         return view('template.list_learning_area_creates_direct_experience_case_front');
    })->name('list-learning-area-creates-direct-experience');

     /* Ncds */
     Route::get('ncds-list',function(){
        return view('template.ncds_list');
     })->name('ncds-list');

     /* thaihealthwatch */
     Route::get('thaihealth-watch',function(){
          return view('template.thaihealth-watch');
     })->name('thaihealth-watch');

          /* Sitemap */
     Route::get('site-map',function(){
               return view('template.sitemap');
     })->name('sitemap');

	Route::get('rss/feed/{slug}', 'FeedController@getIndex')->name('rss-feed');
	Route::match(['get','post'],'/{slug?}', 'HomeController@getIndex')->name('home');
	Route::post('/language/change', 'HomeController@postChangeLanguage')->name('change_language');

});

// Route::get('preview-url/{slug}/{module}', 'HomeController@getPreviewRoomUrl')->name('home.preview.url');
// Route::get('preview/{slug}', 'HomeController@getPreview');

Route::get('preview-menu-url/{slug}', 'HomeController@getPreviewMenuUrl');
Route::get('preview-menu/{slug}', 'HomeController@getPreviewMenu');
Route::post('ckeditor/upload-image','HomeController@postImageUpload');
Route::get('template/AssessmentForm', 'HomeController@getAssessmentForm')->name('AssessmentForm');
Route::get('login/facebook', 'Auth\LoginController@redirectToProvider')->name('login-facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback')->name('callback-facebook');


