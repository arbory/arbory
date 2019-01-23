<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->unique('id');
            $table->unique(['content_type', 'content_id']);

            $table->index('slug');
            $table->index('parent_id');
            $table->index('lft');
            $table->index('rgt');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropUnique('nodes_id_unique');
            $table->dropUnique('nodes_content_type_content_id_unique');

            $table->dropIndex('nodes_slug_index');
            $table->dropIndex('nodes_parent_id_index');
            $table->dropIndex('nodes_lft_index');
            $table->dropIndex('nodes_rgt_index');
            $table->dropIndex('nodes_active_index');
        });
    }
}
