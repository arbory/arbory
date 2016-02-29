<?php

use \CubeSystems\Leaf\Http\Controllers\DashboardController;
use CubeSystems\Leaf\Http\Controllers\LoginController;
use \CubeSystems\Leaf\Http\Controllers\ResourceController;

Route::group( [ 'prefix' => config( 'leaf.uri' ) ], function ()
{

    Route::get( '/', [
        'as' => 'admin.login',
        'uses' => LoginController::class . '@index'
    ] );

    Route::post( '/', [
        'as' => 'admin.sign_in',
        'uses' => LoginController::class . '@login'
    ] );

    Route::delete( '/logout', [
        'as' => 'admin.sign_out',
        'uses' => LoginController::class . '@logout'
    ] );

    Route::get( '/dashboard', [
        'as' => 'admin.dashboard',
        'uses' => DashboardController::class . '@index'
    ] );

    Route::get( 'model/{model}', [
        'as' => 'admin.model.index',
        'uses' => ResourceController::class . '@index'
    ] );

    Route::get( 'model/{model}/create', [
        'as' => 'admin.model.create',
        'uses' => ResourceController::class . '@create'
    ] );

    Route::post( 'model/{model}', [
        'as' => 'admin.model.store',
        'uses' => ResourceController::class . '@store'
    ] );

    Route::get( 'model/{model}/{id}', [
        'as' => 'admin.model.edit',
        'uses' => ResourceController::class . '@edit'
    ] );

    Route::put( 'model/{model}/{id}', [
        'as' => 'admin.model.update',
        'uses' => ResourceController::class . '@update'
    ] );

    Route::get( 'model/{model}/{id}/confirm_destroy', [
        'as' => 'admin.model.confirm_destroy',
        'uses' => ResourceController::class . '@confirmDestroy'
    ] );

    Route::delete( 'model/{model}/{id}', [
        'as' => 'admin.model.destroy',
        'uses' => ResourceController::class . '@destroy'
    ] );

    Route::get( 'model/{model}/{id}/{action}', [
        'as' => 'admin.model.action',
        'uses' => ResourceController::class . '@handleGetAction'
    ] );

    Route::post( 'model/{model}/{id}/{action}', [
        'as' => 'admin.model.action',
        'uses' => ResourceController::class . '@handlePostAction'
    ] );


} );
