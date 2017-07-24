<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingTranslationsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create( 'setting_translations', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->timestamps();
            $table->string( 'setting_name' )->nullable();
            $table->longText( 'value' )->nullable();
            $table->string( 'locale' )->index();
            $table->unique( [ 'setting_name', 'locale' ] );
            $table->foreign( 'setting_name' )->references( 'name' )->on( 'settings' )->onDelete( 'cascade' );
        } );
    }
}