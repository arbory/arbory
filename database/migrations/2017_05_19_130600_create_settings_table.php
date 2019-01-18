<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'settings', function ( Blueprint $table )
        {
            $table->timestamps();
            $table->increments( 'id' );
            $table->string( 'type' );
            $table->string( 'name' )->unique();
            $table->text( 'value' )->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}