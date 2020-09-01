<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->text('from_url');
            $table->text('to_url');
        });
    }

    public function down()
    {
        Schema::drop('redirects');
    }
}
