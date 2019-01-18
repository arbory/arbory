<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create( 'relations', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->timestamps();
            $table->string( 'name' );
            $table->string( 'owner_id' );
            $table->string( 'owner_type' );
            $table->string( 'related_type' );
            $table->string( 'related_id' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}