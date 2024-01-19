<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/article', function (Request $request) {
//     // return $request->room();
// })->middleware('auth:api');

Route::get('/get_learningpartner', 'MediaController@get_learningpartner')->name('admin.api.get_learningpartner');
Route::get('/get_persona_media', 'MediaController@get_persona')->name('admin.api.get_persona');
Route::get('/get_resourcecenter_media', 'MediaController@get_resourcecenter')->name('admin.api.get_resourcecenter');
Route::post('/UpdateMediaThai', 'MediaController@UpdateMediaThai')->name('admin.api.UpdateMediaThai');
Route::post('/get_data_resourcecenter', 'MediaController@get_data_resourcecenter')->name('admin.api.get_data_resourcecenter');
Route::post('/get_resourcecenter_all', 'MediaController@get_resourcecenter_all')->name('admin.api.get_resourcecenter_all');
Route::post('/CategoryData', 'CategoryController@CategoryData')->name('data.category');
Route::post('/IssueData', 'CategoryController@IssueData')->name('data.Issue');
Route::post('/TargetData', 'CategoryController@TargetData')->name('data.Target');
Route::post('/SettingData', 'CategoryController@SettingData')->name('data.Setting');
Route::get('/get_resourcecenter_test', 'MediaController@get_resourcecenter_test')->name('admin.api.get_resourcecenter_test');
Route::post('/MediaLinkGenerateByID', 'MediaController@MediaLinkGenerateByID')->name('admin.api.MediaLinkGenerateByID');

Route::any('/import_excal', 'CategoryController@import_excal');
Route::any('/Apiupdatetags', 'CategoryController@Apiupdatetags');
Route::post('/UpdateStatusMedia', 'CategoryController@UpdateStatusMedia');
