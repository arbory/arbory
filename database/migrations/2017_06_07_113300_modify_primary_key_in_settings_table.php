<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyPrimaryKeyInSettingsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('name')->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropPrimary('name');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->increments('id');
        });
    }
}