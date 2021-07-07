<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/app', 'HomeController@index')->name('home');


/**********************************
        User
 **********************************/

Route::put('users/{user}/profile', 'UserProfileController@update');

Route::post('users/{user}/avatar', 'UserAvatarController@store');

Route::get('users/public/{user}','PublicProfileController@index');

Route::get('users','UserController@index');

/**********************************
        Cases
 **********************************/

Route::get('cases','CasesController@index');

Route::middleware('auth')->post('case', 'CasesController@create');

Route::middleware('auth')->get('cases/{user}','CasesController@getall');

Route::middleware('auth')->get('case/{id}','CasesController@getSingle');

Route::middleware('auth')->put('case/{id}','CasesController@editSingle');

Route::middleware('auth')->delete('case/{id}','CasesController@delete');

Route::middleware('auth')->put('case/{id}/notes','CasesController@editNotes');

Route::middleware('auth')->put('case/{id}/approve','CasesController@approveCase');

Route::middleware('auth')->put('case/{id}/init','CasesController@initCase');

Route::middleware('auth')->put('case/{id}/resolve','CasesController@caseResolve');

Route::middleware('auth')->put('case/{id}/invert','CasesController@invertSingle');

/**********************************
        Meetings
 **********************************/

Route::get('meetings','MeetingsController@index');

Route::middleware('auth')->post('meeting', 'MeetingsController@create');

Route::middleware('auth')->get('meetings/{id}','MeetingsController@getall');

Route::middleware('auth')->put('meeting/{id}','MeetingsController@editSingle');

Route::middleware('auth')->delete('meeting/{id}','MeetingsController@delete'); 

/**********************************
        Profiles
 **********************************/

Route::get('profile/{mediator}','UserProfileController@getProfile');



Route::get('/service-onboarding', function () {
    return view('onboarding');
});

Route::get('/client-onboarding', function () {
    return view('onboarding');
});

Route::middleware('auth:api')->get('messages/{case}', 'ChatsController@fetchMessages');

Route::middleware('auth:api')->post('messages', 'ChatsController@sendMessage');

Route::group(['middleware' => 'auth'], function(){

    Route::get('video_chat', 'VideoChatController@index');
    
    Route::post('auth/video_chat', 'VideoChatController@auth');
    
});


/**********************************
        Especialties
 **********************************/

Route::get('especs','EspecialtiesController@index');

Route::middleware('auth')->post('especs', 'EspecialtiesController@create');

Route::middleware('auth')->delete('especs/{id}','EspecialtiesController@delete');


Route::post('api/signCallback', function() {
    $data = 'Hello API Event Received';
    return response()->json($data, 201);
});
