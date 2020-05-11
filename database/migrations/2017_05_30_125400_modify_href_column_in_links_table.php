<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyHrefColumnInLinksTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->text('href')->change();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->string('href')->change();
        });
    }
}
