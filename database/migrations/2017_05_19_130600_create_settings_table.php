<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('type');
            $table->string('name')->unique();
            $table->text('value')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('settings');
    }
}
