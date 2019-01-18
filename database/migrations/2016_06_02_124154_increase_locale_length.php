<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseLocaleLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translator_languages', function (Blueprint $table) {
            $table->string('locale', 10)->change();
        });

        Schema::table('translator_translations', function (Blueprint $table) {
            $table->string('locale', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table('translator_languages', function (Blueprint $table) {
            $table->string('locale', 6)->change();
        });

        Schema::table('translator_translations', function (Blueprint $table) {
            $table->string('locale', 6)->change();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}