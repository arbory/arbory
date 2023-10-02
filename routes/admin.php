<?php

use Arbory\Base\Http\Controllers\Admin\DashboardController;
use Arbory\Base\Http\Controllers\Admin\LanguageController;
use Arbory\Base\Http\Controllers\Admin\NodesController;
use Arbory\Base\Http\Controllers\Admin\RedirectsController;
use Arbory\Base\Http\Controllers\Admin\RolesController;
use Arbory\Base\Http\Controllers\Admin\SettingsController;
use Arbory\Base\Http\Controllers\Admin\TranslationsController;
use Arbory\Base\Http\Controllers\Admin\UsersController;

Route::get('/', ['as' => 'login.form', 'uses' => 'Admin\SecurityController@getLogin']);
Route::post('login', ['as' => 'login.attempt', 'uses' => 'Admin\SecurityController@postLogin']);
Route::get('/confirm', ['as' => 'confirm.form', 'uses' => 'Admin\SecurityController@getConfirm']);
Route::post('confirm', ['as' => 'confirm.attempt', 'uses' => 'Admin\SecurityController@postLogin']);
Route::post('logout', ['as' => 'logout', 'uses' => 'Admin\SecurityController@postLogout']);

Route::group(['middleware' => 'arbory.admin_auth'], function () {
    Route::group(['middleware' => 'arbory.admin_module_access'], function () {
        Admin::modules()->register(DashboardController::class);
        Admin::modules()->register(UsersController::class);
        Admin::modules()->register(RolesController::class);
        Admin::modules()->register(NodesController::class);
        Admin::modules()->register(TranslationsController::class);
        Admin::modules()->register(SettingsController::class);
        Admin::modules()->register(RedirectsController::class);
        Admin::modules()->register(LanguageController::class);

        Route::get('translations/edit/{namespace}/{group}/{item}', [
            'as' => 'translations.edit.locales',
            'uses' => 'Admin\TranslationsController@edit',
        ]);

        Route::post('translations/update', [
            'as' => 'translations.update.locales',
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

    Route::get('profile/two-factor', [
        'as' => 'profile.two-factor',
        'uses' => 'Admin\ProfileController@twoFactor',
    ]);

    Route::get('profile/two-factor/enable', [
        'as' => 'profile.two-factor.enable',
        'uses' => 'Admin\ProfileController@enableTwoFactor',
    ]);

    Route::post('profile/two-factor/activate', [
        'as' => 'profile.two-factor.activate',
        'uses' => 'Admin\ProfileController@activateTwoFactor',
    ]);

    Route::post('profile/two-factor/disable', [
        'as' => 'profile.two-factor.disable',
        'uses' => 'Admin\ProfileController@disableTwoFactor',
    ]);

    Route::post('search', [
        'as' => 'search',
        'uses' => 'Admin\GlobalSearchController@search',
    ]);
});
