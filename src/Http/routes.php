<?php

Route::group( [ 'prefix' => config( 'leaf.uri' ) ], function () {

    Route::get( '/', [
        'uses' => \CubeSystems\Leaf\Http\Controllers\DashboardController::class . '@getIndexPage',
        'as' => 'admin.index'
    ] );

    Route::resource( 'users',
        \CubeSystems\Leaf\Http\Controllers\UsersController::class
    );

} );
