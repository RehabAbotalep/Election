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

Route::post('token/submit','Api\NotificationController@submitToken');

Route::group(['namespace' =>'Api'], function () {

	Route::post('import', 'ImportController@import');

	Route::post('login','AuthController@login');

	Route::get('profile','AuthController@profile')->middleware('auth:api');


	Route::group(['middleware' => ['role:admin' ,'auth:api']], function () {

		Route::resource('users' , 'UserController');

		Route::get('areas' , 'PredefineDataController@areas');

		Route::get('jobs' , 'PredefineDataController@jobs');

		Route::get('constituencies' , 'PredefineDataController@constituencies');

		Route::get('account/types' , 'PredefineDataController@accountTypes');

		Route::get('permissions' , 'PredefineDataController@permissions');

		Route::get('groups', 'GroupController@groups');

		Route::get('groups/{id}', 'GroupController@show');

		Route::delete('groups/{id}', 'GroupController@destroy');

		Route::put('groups/{id}', 'GroupController@update');

		Route::get('groups/{id}/electors', 'GroupController@electors');

		Route::delete('groups/{group_id}/electors/{elector_id}', 'GroupController@deleteElector');

		Route::get('committees' , 'CommitteeController@committees');

		Route::put('committees/{id}' , 'CommitteeController@update');

		Route::get('committees/{id}/electors' , 'CommitteeController@electors');

		Route::post('electors/{id}/vote','CommitteeController@vote');

		Route::get('electors','ElectorController@electors');

		Route::get('electors/{id}','ElectorController@electorDetails');

		Route::put('electors/{id}','ElectorController@updateElector');

		Route::post('electors/groups/add','ElectorController@addToGroup');

		Route::resource('diwaniyahs', 'DiwaniyahController');

		Route::get('statistics','UserController@statistics');

		Route::get('notifications','NotificationController@notifications');

		Route::post('notifications/send','NotificationController@send');

		Route::get('notification/users','NotificationController@users');
		
		Route::get('users/{id}/notifications','NotificationController@userNotification');

		Route::group(['middleware' => ['permission:اضافة مجموعة']], function () {
			Route::post('groups/add', 'GroupController@add');
    
		});
	});
	


});


