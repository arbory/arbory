<?php

use CubeSystems\Leaf\Http\Controllers\Admin\DashboardController;
use CubeSystems\Leaf\Http\Controllers\Admin\LoginController;
use CubeSystems\Leaf\Http\Controllers\Admin\ResourceController;

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

    Route::delete( 'model/{model}/{id}', [
        'as' => 'admin.model.destroy',
        'uses' => ResourceController::class . '@destroy'
    ] );

    Route::get( 'model/{model}/dialog/{dialog}', [
        'as' => 'admin.model.dialog',
        'uses' => ResourceController::class . '@dialog'
    ] );

} );
