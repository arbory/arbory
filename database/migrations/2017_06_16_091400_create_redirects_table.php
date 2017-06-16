<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'redirects', function ( Blueprint $table )
        {
            $table->timestamps();
            $table->increments( 'id' );
            $table->string( 'from_url' );
            $table->string( 'to_url' );
        } );
    }
}