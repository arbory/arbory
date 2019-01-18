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
            $table->text( 'from_url' );
            $table->text( 'to_url' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirects');
    }
}