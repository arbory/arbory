<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('owner_id');
            $table->string('owner_type');
            $table->string('original_name');
            $table->string('local_name');
            $table->string('disk');
            $table->string('sha1', 40);
            $table->integer('size');
            $table->timestamps();

            $table->index('owner_id');
            $table->index('owner_type');
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
}
