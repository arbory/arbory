<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOptionalFieldsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'nodes', function ( Blueprint $table )
        {
            $table->string( 'content_type' )->nullable()->change();
            $table->integer( 'content_id' )->nullable()->change();
            $table->integer( 'item_position' )->nullable()->change();
            $table->boolean( 'active' )->default(0)->change();
            $table->string( 'locale', 6 )->nullable()->change();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'nodes', function ( Blueprint $table )
        {
            $table->string( 'content_type' )->change();
            $table->integer( 'content_id' )->change();
            $table->integer( 'item_position' )->change();
            $table->boolean( 'active' )->change();
            $table->string( 'locale', 6 )->change();
        } );
    }
}
