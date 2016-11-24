<?php

namespace Leaf\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Schema;

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
        Schema::table( 'leaf_files', function ( Blueprint $table )
        {
            $table->uuid( 'id' );
            $table->string( 'owner_id' );
            $table->string( 'original_name' );
            $table->string( 'disk' );
            $table->string( 'sha1', 40 );
            $table->integer( 'size' );
            $table->timestamps();

            $table->index( 'owner_id' );
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
