<?php

Route::get( '/', [ 'as' => 'login.form', 'uses' => 'Admin\SessionController@getLogin' ] );
Route::post( 'login', [ 'as' => 'login.attempt', 'uses' => 'Admin\SessionController@postLogin' ] );
Route::post( 'logout', [ 'as' => 'logout', 'uses' => 'Admin\SessionController@postLogout' ] );

Route::group( [ 'middleware' => 'leaf.admin_auth' ], function ()
{
    AdminModule::register( CubeSystems\Leaf\Http\Controllers\Admin\MenuBuilderController::class );
    AdminModule::register( CubeSystems\Leaf\Http\Controllers\Admin\NodesController::class );
    AdminModule::register( CubeSystems\Leaf\Http\Controllers\Admin\RolesController::class );
    AdminModule::register( CubeSystems\Leaf\Http\Controllers\Admin\UsersController::class );
    AdminModule::register( CubeSystems\Leaf\Http\Controllers\Admin\SettingsController::class );

    Route::get( 'dashboard', [
        'as' => 'dashboard',
        'uses' => 'Admin\DashboardController@index'
    ] );

    Route::get( 'translations/list', [
        'as' => 'translations.index',
        'uses' => 'Admin\TranslationsController@index'
    ] );

    Route::get( 'translations/edit/{namespace}-{group}-{item}', [
        'as' => 'translations.edit',
        'uses' => 'Admin\TranslationsController@edit'
    ] );

    Route::post( 'translations/update', [
        'as' => 'translations.update',
        'uses' => 'Admin\TranslationsController@store'
    ] );
} );
