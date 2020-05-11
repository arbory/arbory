<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTranslationsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('setting_name')->nullable();
            $table->longText('value')->nullable();
            $table->string('locale')->index();
            $table->unique(['setting_name', 'locale']);
            $table->foreign('setting_name')->references('name')->on('settings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('setting_translations');
    }
}
