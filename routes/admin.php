<?php

Route::get('/', ['as' => 'login.form', 'uses' => 'Admin\SecurityController@getLogin']);
Route::post('login', ['as' => 'login.attempt', 'uses' => 'Admin\SecurityController@postLogin']);
Route::post('logout', ['as' => 'logout', 'uses' => 'Admin\SecurityController@postLogout']);

Route::group(['middleware' => 'arbory.admin_auth'], function () {
    Route::group(['middleware' => 'arbory.admin_module_access'], function () {
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\DashboardController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\UsersController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\RolesController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\NodesController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\TranslationsController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\SettingsController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\RedirectsController::class);
        Admin::modules()->register(\Arbory\Base\Http\Controllers\Admin\LanguageController::class);

        Route::get('translations/edit/{namespace}/{group}/{item}', [
            'as' => 'translations.edit',
            'uses' => 'Admin\TranslationsController@edit',
        ]);

        Route::post('translations/update', [
            'as' => 'translations.update',
            'uses' => 'Admin\TranslationsController@store',
        ]);

        Route::post('language/{language}/disable', [
            'as' => 'language.disable',
            'uses' => 'Admin\LanguageController@disable',
        ]);

        Route::post('language/{language}/restore', [
            'as' => 'language.restore',
            'uses' => 'Admin\LanguageController@restore',
        ]);
    });

    Route::post('file-manager/upload', [
        'as' => 'filemanager.upload',
        'uses' => 'Admin\UploadController@upload',
    ]);
});
