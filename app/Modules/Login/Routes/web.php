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
    Route::get('login', 'IndexController@getIndex')->name('admin.login');
    Route::post('login', 'IndexController@postIndex')->name('admin.login');
    Route::post('logout', 'IndexController@postLogout')->name('admin.logout');
    
});

//'prefix' => LaravelLocalization::setLocale(),'middleware' =>['localeSessionRedirect', 'localizationRedirect']
Route::group([], function()
{
	Route::post('user/login', 'IndexController@postLogin')->name('login');
	Route::post('user/logout', 'IndexController@postFrontLogout')->name('logout');
});

Route::get('sso/login', 'IndexController@ssoLogin')->name('sso-login');
Route::get('sso/login/callback', 'IndexController@ssoLoginCallback')->name('sso-login-callback');
Route::get('sso/login/callback-user', 'IndexController@ssoLoginCallbackUser')->name('sso-login-callback-user');
Route::get('auth/facebook', 'IndexController@redirectToFacebook')->name('facebook-login');
Route::get('auth/facebook/callback', 'IndexController@handleFacebookCallback')->name('facebook-login-callback');
Route::get('facebook/data-deletion-request/callback', 'IndexController@FacebookDataDeletionRequestCallback')->name('facebook-data-deletion-request-callback');
Route::get('pdpa/callback/success', 'IndexController@PdpaCallbackSucess')->name('pdpa-success-callback');
Route::get('pdpa/callback/fail', 'IndexController@PdpaCallbackFail')->name('pdpa-fail-callback');
Route::post('pdpa/udi-key', 'IndexController@PdpaUidKey')->name('pdpa-uid-key');
Route::post('pdpa/receive-result', 'IndexController@PdpaReceiveResult')->name('pdpa-receive-result');

Route::get('sso/login/callbacktest', 'IndexController@ssoLoginCallbacktest')->name('sso-login-callback-test');

