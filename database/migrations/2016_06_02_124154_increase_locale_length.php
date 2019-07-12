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
        Schema::table('translator_translations', function (Blueprint $table) {
            $table->dropForeign('translator_translations_locale_foreign');
        });

        Schema::table('translator_languages', function (Blueprint $table) {
            $table->string('locale', 10)->change();
        });

        Schema::table('translator_translations', function (Blueprint $table) {
            $table->string('locale', 10)->change();
            $table->foreign('locale')->references('locale')->on('translator_languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translator_translations', function (Blueprint $table) {
            $table->dropForeign('translator_translations_locale_foreign');
            $table->dropUnique('translator_translations_locale_namespace_group_item_unique');
        });

        Schema::table('translator_languages', function (Blueprint $table) {
            $table->dropUnique('translator_languages_locale_unique');
        });

        Schema::table('translator_languages', function (Blueprint $table) {
            $table->string('locale', 6)->unique()->change();
        });

        Schema::table('translator_translations', function (Blueprint $table) {
            $table->string('locale', 6)->change();
            $table->foreign('locale')->references('locale')->on('translator_languages');
            $table->unique(['locale', 'namespace', 'group', 'item']);
        });
    }
}
