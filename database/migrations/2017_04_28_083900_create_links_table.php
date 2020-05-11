<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('href');
            $table->boolean('new_tab');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
    }
}
