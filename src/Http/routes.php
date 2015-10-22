<?php

Route::group( [ 'prefix' => config( 'leaf.uri' ) ], function () {

    Route::resource( 'users',
        \CubeSystems\Leaf\Http\Controllers\UsersController::class
    );

} );
