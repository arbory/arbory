<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuBuilderTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create( 'admin_menu_items', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'title' );
            $table->string( 'parent' )->nullable();
            $table->text( 'module' )->nullable();
            $table->integer( 'after' )->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        } );

        Schema::create( 'admin_role_menu_items', function( Blueprint $table )
        {
            $table->integer( 'menu_item_id' )->unsigned();
            $table->integer( 'role_id' )->unsigned();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->primary( [ 'menu_item_id', 'role_id' ] );
        } );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::drop( 'admin_menu_items' );
        Schema::drop( 'admin_role_menu_items' );
    }
}