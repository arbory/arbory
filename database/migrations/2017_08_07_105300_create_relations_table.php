<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('owner_id');
            $table->string('owner_type');
            $table->string('related_type');
            $table->string('related_id');
        });
    }

    public function down()
    {
        Schema::drop('relations');
    }
}
