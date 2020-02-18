<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageLinkedNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('language_linked_nodes')) {
            return;
        }

        Schema::create('language_linked_nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('link');
            $table->integer('language_id')->nullable()->unsigned();
            $table->string('node_id')->nullable();
            $table->timestamps();
        });

        Schema::table('language_linked_nodes', function (Blueprint $table) {
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('translator_languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_linked_nodes');
    }
}
