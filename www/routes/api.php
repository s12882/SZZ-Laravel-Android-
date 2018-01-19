<?php

use Illuminate\Http\Request;

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

Route::post('login', 'API\LoginController@login');

Route::group(['middleware' => 'auth:api'], function(){
	Route::post('verify', 'API\LoginController@verifyToken');
    Route::post('details', 'API\LoginController@details');

    Route::group(['prefix' => 'notifications'], function(){
        Route::post('/', 'API\NotificationsController@saveDevice');
        });

        Route::group(['prefix' => 'roles'], function(){
        Route::post('all', 'API\RoleController@All');
        Route::post('get', 'API\RoleController@get');
        });

    Route::group(['prefix' => 'section'], function(){
        Route::post('/', 'API\SectionController@index');
        Route::post('create', 'API\SectionController@create');
        Route::post('get', 'API\SectionController@get');
        Route::post('update', 'API\SectionController@update');
        Route::post('destroy', 'API\SectionController@destroy');
        Route::post('staff', 'API\SectionController@getStaff');
    });

    Route::group(['prefix' => 'user'], function(){
        Route::post('/', 'API\UserController@index');
        Route::post('create', 'API\UserController@create');
        Route::post('update', 'API\UserController@update');
        Route::post('destroy', 'API\UserController@destroy'); 
        Route::post('profile', 'API\UserController@profile');   
        Route::post('activate', 'API\UserController@activate');  
        Route::post('deactivate', 'API\UserController@deactivate');  
        Route::post('setpin', 'API\UserController@setpin');  
        Route::post('savedevice', 'API\UserController@saveDevice'); 
        Route::post('tasks', 'API\UserController@getUserTasks'); 
    });

    Route::group(['prefix' => 'task'], function(){
	    Route::post('/', 'API\TaskController@index');
        Route::post('create', 'API\TaskController@create');
        Route::post('get', 'API\TaskController@get');
        Route::post('update', 'API\TaskController@update');
        Route::post('destroy', 'API\TaskController@destroy'); 
        Route::post('profile', 'API\TaskController@profile');  
        Route::post('ofsection', 'API\TaskController@getBySection');  
        Route::post('reserve', 'API\TaskController@reserve');  
        Route::post('users', 'API\TaskController@getAssignedUsers');         
    });

     Route::group(['prefix' => 'item'], function(){
	    Route::post('/', 'API\ItemController@index');
        Route::post('create', 'API\ItemController@create');
        Route::post('get', 'API\ItemController@get');
        Route::post('update', 'API\ItemController@update');
        Route::post('destroy', 'API\ItemController@destroy'); 
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
