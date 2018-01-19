<?php

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
    redirect()->route('login');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@index')->name('login');
    Route::post('login', 'LoginController@login')->name('post.login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset_form');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.request');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'user'], function () {
        Route::group(['middleware' => 'permission:list users'], function () {
            Route::post('datatables', 'UserController@datatables')->name('user.datatables');
            Route::get('datatables', 'UserController@datatables')->name('user.datatables');
            Route::get('', 'UserController@index')->name('user.index');
        });
        Route::group(['middleware' => 'permission:acivate/deactivate users'], function () {
            Route::get('/{user}/activate', 'UserController@activate')->name('user.activate');
            Route::get('/{user}/deactivate', 'UserController@deactivate')->name('user.deactivate');
        });
        Route::group(['middleware' => 'permission:create user'], function () {
            Route::get('create', 'UserController@create')->name('user.create');
            Route::post('', 'UserController@store')->name('user.store');
        });
        Route::patch('/{user}', 'UserController@update')->name('user.update');
        Route::get('/{id}', function(){throw new \ErrorException();});
        Route::get('/{user}/edit', 'UserController@edit')->name('user.edit')->middleware('permission:update user');
        Route::delete('/{id}', 'UserController@destroy')->name('user.destroy')->middleware('permission:delete user');
    });

    Route::group(['prefix' => 'section'], function(){
      Route::group(['middleware' => 'permission:list sections'], function(){
        Route::post('/datatables', 'SectionController@treedata')->name('section.treedata');
        Route::get('', 'SectionController@index')->name('section.index');
      });
      Route::group(['middleware' => 'permission:create section'], function(){
        Route::get('create/{parent}', 'SectionController@create')->name('section.create_with_parent');
        Route::get('create/', 'SectionController@create')->name('section.create');
        Route::post('', 'SectionController@store')->name('section.store');
      });

      Route::group(['middleware' => 'permission:update section'], function () {
          Route::get('/{section}/edit', 'SectionController@edit')->name('section.edit');
          Route::patch('/{section}', 'SectionController@update')->name('section.update');
      });
      Route::get('/{id}', function(){throw new \ErrorException();});
      Route::delete('/{id}', 'SectionController@destroy')->name('section.destroy')->middleware('permission:delete section');
    });

    Route::group(['prefix' => 'role'], function(){
        Route::group(['middleware' => 'permission:list roles'], function(){
          Route::post('/datatables', 'RoleController@datatables')->name('role.datatables');
          Route::get('', 'RoleController@index')->name('role.index');
        });
        Route::group(['middleware' => 'permission:create role'], function(){
          Route::get('create', 'RoleController@create')->name('role.create');
          Route::post('', 'RoleController@store')->name('role.store');
        });
        Route::group(['middleware' => 'permission:update role'], function () {
            Route::get('/{role}/edit', 'RoleController@edit')->name('role.edit');
            Route::patch('/{role}', 'RoleController@update')->name('role.update');
        });
        Route::get('/{id}', function(){throw new \ErrorException();});
        Route::delete('/{id}', 'RoleController@destroy')->name('role.destroy')->middleware('permission:delete role');
    });

    Route::group(['prefix' => 'task'], function(){
        Route::get('', 'TaskController@index')->name('task.index');
        Route::post('/datatables', 'TaskController@datatables')->name('task.datatables');
        
        Route::group(['middleware' => 'permission:create task'], function(){
            Route::get('/create', 'TaskController@create')->name('task.create');
            Route::post('', 'TaskController@store')->name('task.store');
        });
        
        Route::group(['middleware' => 'permission:update task'], function(){
            Route::get('/{task}/edit', 'TaskController@edit')->name('task.edit');
            Route::patch('/{task}', 'TaskController@update')->name('task.update');
        });
        
        Route::get('/{task}', 'TaskController@show')->name('task.show');
        Route::delete('/{id}', 'TaskController@destroy')->name('task.destroy')->middleware('permission:delete task');
        Route::post('/{id}/reserve', 'TaskController@reserve')->name('task.reserve');
        Route::post('/{id}/forwardToCheck', 'TaskController@forwardToCheck')->name('task.forwardToCheck');
        Route::post('{task}/manageItems', 'TaskController@manageItems')->name('task.manageItems');
        Route::delete('destroy_image/{id}', 'TaskController@destroy_image')->name('task.destroy_image');
        Route::get('download_file/{id}', 'TaskController@download_image')->name('task.download_image');
    });

    Route::group(['prefix' => 'item'], function(){
    
        Route::post('/datatables', 'ItemController@datatables')->name('item.datatables');
        Route::get('', 'ItemController@index')->name('item.index');

        Route::group(['middleware' => 'permission:create item '], function(){
            Route::get('create', 'ItemController@create')->name('item.create');
            Route::post('', 'ItemController@store')->name('item.store');
        });

        Route::group(['middleware' => 'permission:update item'], function(){
            Route::get('/{item}/edit', 'ItemController@edit')->name('item.edit');
            Route::patch('/{item}', 'ItemController@update')->name('item.update');
        });
        Route::get('/{id}', function(){throw new \ErrorException();});
        Route::delete('/{id}', 'ItemController@destroy')->name('item.destroy')->middleware('permission:delete item');
    });

    Route::group(['prefix' => 'item_category'], function(){
        
            Route::post('/datatables', 'ItemCategoryController@datatables')->name('item_category.datatables');
            Route::get('', 'ItemCategoryController@index')->name('item_category.index');
    
            Route::group(['middleware' => 'permission:create item_category'], function(){
                Route::get('create', 'ItemCategoryController@create')->name('item_category.create');
                Route::post('', 'ItemCategoryController@store')->name('item_category.store');
            });
    
            Route::group(['middleware' => 'permission:update item_category'], function(){
            Route::get('/{item_category}/edit', 'ItemCategoryController@edit')->name('item_category.edit');
            Route::patch('/{item_category}', 'ItemCategoryController@update')->name('item_category.update');
            });

            Route::get('/{id}', function(){throw new \ErrorException();});
            Route::delete('/{id}', 'ItemCategoryController@destroy')->name('item_category.destroy')->middleware('permission:delete item_category');
        });


        Route::group(['prefix' => 'warehouse_document'], function(){
            
            Route::group(['middleware' => 'permission:list warehouse_document'], function(){
                Route::post('/datatables', 'WarehouseDocumentController@datatables')->name('warehouse_document.datatables');
                Route::get('/datatables', 'WarehouseDocumentController@datatables')->name('warehouse_document.datatables');
                Route::get('', 'WarehouseDocumentController@index')->name('warehouse_document.index');
            });
            Route::get('create', 'WarehouseDocumentController@create')->name('warehouse_document.create');
            Route::get('warehouse_document/create/{task}', 'WarehouseDocumentController@create')->name('warehouse_document.create_for_task');
            Route::post('', 'WarehouseDocumentController@store')->name('warehouse_document.store');
    
            Route::get('/{id}', function(){throw new \ErrorException();});
            Route::delete('/{id}', 'WarehouseDocumentController@destroy')->name('warehouse_document.destroy')->middleware('permission:delete warehouse_document');;

            });


    Route::group(['prefix' => 'comment'], function(){
        Route::post('all', 'CommentController@getComments')->name('comment.all');
        Route::post('new', 'CommentController@getNewComments')->name('comment.new');
        Route::post('comment', 'CommentController@store')->name('comment.store');
        Route::delete('{id}', 'CommentController@destroy')->name('comment.destroy');
    });

    Route::get('profile', 'UserController@profile')->name('profile.index');
    Route::get('profile#edit', 'UserController@profile')->name('profile.edit');

});
