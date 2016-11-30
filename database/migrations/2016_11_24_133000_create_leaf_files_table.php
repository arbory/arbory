<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateLeafFilesTable
 * @package Leaf\database\migrations
 */
class CreateLeafFilesTable extends Migration
{
    /**
     *
     */
    public function up()
    {
        Schema::create( 'leaf_files', function ( Blueprint $table )
        {
            $table->uuid( 'id' );
            $table->string( 'owner_id' );
            $table->string( 'owner_class' );
            $table->string( 'original_name' );
            $table->string( 'local_name' );
            $table->string( 'disk' );
            $table->string( 'sha1', 40 );
            $table->integer( 'size' );
            $table->timestamps();

            $table->index( 'owner_id' );
            $table->index( 'owner_class' );
        } );
    }

    /**
     *
     */
    public function down()
    {
        Schema::drop( 'leaf_files' );
    }
}
