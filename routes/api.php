<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('users','UserController@index');

Route::middleware('auth')->post('case', 'CasesController@create');

Route::middleware('auth:api')->get('cases','CasesController@index');

Route::middleware('auth:api')->get('cases/{user}','CasesController@getall');

Route::middleware('auth:api')->get('case/{id}','CasesController@getSingle');

Route::middleware('auth:api')->put('case/{id}','CasesController@editSingle');

Route::middleware('auth')->delete('case/{id}','CasesController@delete');

Route::middleware('auth')->put('case/{id}/notes','CasesController@editNotes');

Route::middleware('auth')->put('case/{id}/approve','CasesController@approveCase');

Route::middleware('auth')->put('case/{id}/init','CasesController@initCase');

Route::middleware('auth')->put('case/{id}/resolve','CasesController@caseResolve');

Route::middleware('auth:api')->put('case/{id}/invert','CasesController@invertSingle');

/**********************************
        Meetings
 **********************************/

Route::get('meetings','MeetingsController@index');

Route::middleware('auth')->post('meeting', 'MeetingsController@create');

Route::middleware('auth')->get('meetings/{id}','MeetingsController@getall');

Route::middleware('auth')->get('meeting/{id}','MeetingsController@getSingle');

Route::middleware('auth')->put('meeting/{id}','MeetingsController@editSingle');

Route::middleware('auth')->delete('meeting/{id}','MeetingsController@delete');

/**********************************
        Documents
 **********************************/

// Route::post('signReport', 'MeetingsController@create');

Route::middleware('auth:api')->post('document', 'DocumentsController@create');

Route::middleware('auth:api')->get('documents/{id}','DocumentsController@getall');

Route::middleware('auth:api')->get('document/templates','DocumentsController@getTemplates');

Route::middleware('auth:api')->put('document/{id}','DocumentsController@editSingle');

Route::middleware('auth:api')->post('document/{id}/attach','DocumentsController@attach');

Route::middleware('auth:api')->post('document/{id}/save-signature','DocumentsController@saveSign');

Route::middleware('auth:api')->get('document/{id}/download','DocumentsController@downloadPdf');

Route::middleware('auth:api')->post('document/sign/{id}','DocumentsController@helloSign');

Route::middleware('auth:api')->delete('document/{id}','DocumentsController@delete');

/**********************************
        Chat
 **********************************/

Route::middleware('auth:api')->get('messages/{case}', 'ChatsController@fetchMessages');

Route::middleware('auth:api')->post('messages', 'ChatsController@sendMessage');

Route::put('users/{user}/profile','UserProfileController@update');

Route::post('users/{user}/avatar', 'UserAvatarController@store');

Route::get('users/public/{user}','PublicProfileController@index');

/**********************************
        Especialties
 **********************************/

Route::get('especs','EspecialtiesController@index');

Route::middleware('auth')->post('especs', 'EspecialtiesController@create');

Route::middleware('auth')->delete('especs/{id}','EspecialtiesController@delete');

/**********************************
        Profiles
 **********************************/

Route::get('profile/{mediator}','UserProfileController@getProfile');


Route::post('api/signCallback', function(Request $request) {
        $data = 'Hello API Event Received';
        return response()->json($data, 201);
    });