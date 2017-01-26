<?php

Route::get( 'login', [ 'as' => 'admin.login.form', 'uses' => 'Admin\SessionController@getLogin' ] );
Route::post( 'login', [ 'as' => 'admin.login.attempt', 'uses' => 'Admin\SessionController@postLogin' ] );
Route::post( 'logout', [ 'as' => 'admin.logout', 'uses' => 'Admin\SessionController@postLogout' ] );

Route::group( [ 'middleware' => 'leaf.admin_auth' ], function ()
{
    Route::resource( 'users', 'Admin\UserController' );

    Route::resource( 'roles', 'Admin\RoleController' );

    Route::get( 'dashboard', [
        'as' => 'admin.dashboard',
        'uses' => 'Admin\DashboardController@index'
    ] );

    Route::get( 'model/{model}', [
        'as' => 'admin.model.index',
        'uses' => 'Admin\CrudFrontController@index'
    ] );

    Route::get( 'model/{model}/create', [
        'as' => 'admin.model.create',
        'uses' => 'Admin\CrudFrontController@create'
    ] );

    Route::post( 'model/{model}', [
        'as' => 'admin.model.store',
        'uses' => 'Admin\CrudFrontController@store'
    ] );

    Route::get( 'model/{model}/{id}', [
        'as' => 'admin.model.edit',
        'uses' => 'Admin\CrudFrontController@edit'
    ] );

    Route::put( 'model/{model}/{id}', [
        'as' => 'admin.model.update',
        'uses' => 'Admin\CrudFrontController@update'
    ] );

    Route::delete( 'model/{model}/{id}', [
        'as' => 'admin.model.destroy',
        'uses' => 'Admin\CrudFrontController@destroy'
    ] );

    Route::get( 'model/{model}/dialog/{dialog}', [
        'as' => 'admin.model.dialog',
        'uses' => 'Admin\CrudFrontController@dialog'
    ] );

    Route::get( 'model/{model}/api/{api}', [
        'as' => 'admin.model.api',
        'uses' => 'Admin\CrudFrontController@api'
    ] );

    Route::get( 'translations/list', [
        'as' => 'admin.translations.index',
        'uses' => 'Admin\TranslationsController@index'
    ] );

    Route::get( 'translations/edit/{namespace}-{group}-{item}', [
        'as' => 'admin.translations.edit',
        'uses' => 'Admin\TranslationsController@edit'
    ] );

    Route::post( 'translations/update', [
        'as' => 'admin.translations.update',
        'uses' => 'Admin\TranslationsController@store'
    ] );
} );
