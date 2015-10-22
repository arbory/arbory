<?php

Route::group( array( 'prefix' => config( 'leaf.uri' ) ), function () {

    Route::resource( '/users/users', \CubeSystems\Leaf\Http\Controllers\UsersController::class );

} );
