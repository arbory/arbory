<?php

use Arbory\Base\Auth\Users\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderColumnToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_users', function ( Blueprint $table ) {
            $table->string( 'provider' )->default( User::PROVIDER );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function ( Blueprint $table ) {
            $table->removeColumn( 'provider' );
        });
    }
}
