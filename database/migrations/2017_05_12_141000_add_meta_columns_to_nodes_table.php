<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaColumnsToNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_author');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('meta_description');
        });
    }
}
