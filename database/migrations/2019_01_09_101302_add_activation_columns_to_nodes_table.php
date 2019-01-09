<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivationColumnsToNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dateTime('expire_at')->nullable();
            $table->dateTime( 'activate_at' )->nullable();
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
            $table->tinyInteger( 'active' )->default( 0 );
            $table->dropColumn('expire_at');
            $table->dropColumn( 'activate_at' );
        });
    }
}
