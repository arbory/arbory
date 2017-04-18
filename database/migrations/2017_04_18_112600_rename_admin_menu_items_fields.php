<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameAdminMenuItemsFields extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table( 'admin_menu_items', function( Blueprint $table )
        {
            $table->renameColumn( 'parent', 'parent_id' );
            $table->renameColumn( 'after', 'after_id' );
        } );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table( 'admin_menu_items', function( Blueprint $table )
        {
            $table->renameColumn( 'parent_id', 'parent' );
            $table->renameColumn( 'after_id', 'after' );
        } );
    }
}