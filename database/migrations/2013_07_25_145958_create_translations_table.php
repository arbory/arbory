<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translator_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale', 6);
            $table->string('namespace', 150)->default('*');
            $table->string('group', 150);
            $table->string('item', 150);
            $table->text('text');
            $table->boolean('unstable')->default(false);
            $table->boolean('locked')->default(false);
            $table->timestamps();
            $table->foreign('locale')->references('locale')->on('translator_languages');
            $table->unique(['locale', 'namespace', 'group', 'item']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translator_translations');
    }
}