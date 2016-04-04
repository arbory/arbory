<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'nodes', function ( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'name' );
            $table->string( 'slug' );
            $table->integer( 'parent_id' )->nullable();
            $table->integer( 'lft' )->nullable();
            $table->integer( 'rgt' )->nullable();
            $table->integer( 'depth' )->nullable();
            $table->string( 'content_type' );
            $table->integer( 'content_id' );
            $table->integer( 'item_position' );
            $table->tinyInteger( 'active' );
            $table->string( 'locale', 6 );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nodes');
    }
}
