<?php

use CubeSystems\Leaf\Http\Controllers\Admin\DashboardController;
use CubeSystems\Leaf\Http\Controllers\Admin\LoginController;
use CubeSystems\Leaf\Http\Controllers\Admin\ResourceController;

Route::get( 'login', [ 'as' => 'admin.login.form', 'uses' => 'Admin\SessionController@getLogin' ] );
Route::post( 'login', [ 'as' => 'admin.login.attempt', 'uses' => 'Admin\SessionController@postLogin' ] );
Route::post( 'logout', [ 'as' => 'admin.logout', 'uses' => 'Admin\SessionController@postLogout' ] );

Route::group( [ 'middleware' => 'leaf.admin_auth' ], function ()
{
//    Route::resource( 'users', 'Admin\UserController', [ 'as' => 'admin' ] );
//
//    Route::resource( 'roles', 'Admin\RoleController', [ 'as' => 'admin' ] );

    Route::get( 'dashboard', [
        'as' => 'admin.dashboard',
        'uses' => 'Admin\DashboardController@index'
    ] );

    Route::get( 'model/{model}', [
        'as' => 'admin.model.index',
        'uses' => 'Admin\ResourceController@index'
    ] );

    Route::get( 'model/{model}/create', [
        'as' => 'admin.model.create',
        'uses' => 'Admin\ResourceController@create'
    ] );

    Route::post( 'model/{model}', [
        'as' => 'admin.model.store',
        'uses' => 'Admin\ResourceController@store'
    ] );

    Route::get( 'model/{model}/{id}', [
        'as' => 'admin.model.edit',
        'uses' => 'Admin\ResourceController@edit'
    ] );

    Route::put( 'model/{model}/{id}', [
        'as' => 'admin.model.update',
        'uses' => 'Admin\ResourceController@update'
    ] );

    Route::delete( 'model/{model}/{id}', [
        'as' => 'admin.model.destroy',
        'uses' => 'Admin\ResourceController@destroy'
    ] );

    Route::get( 'model/{model}/dialog/{dialog}', [
        'as' => 'admin.model.dialog',
        'uses' => 'Admin\ResourceController@dialog'
    ] );

    Route::get( 'model/{model}/api/{api}', [
        'as' => 'admin.model.api',
        'uses' => 'Admin\ResourceController@api'
    ] );
} );
