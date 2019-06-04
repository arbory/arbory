<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsInLinksTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->text('href')->nullable()->change();
        });
    }
}
