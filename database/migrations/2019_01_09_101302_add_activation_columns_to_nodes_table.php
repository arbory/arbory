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
            $table->dateTime('activate_at')->nullable();
            $table->dateTime('expire_at')->nullable();
        });

        DB::table('nodes')->where('active', 1)->update([
            'activate_at' => date('Y-m-d H:i')
        ]);

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table)
        {
            $table->tinyInteger('active')->default(0);
        });

        DB::table('nodes')->where('activate_at', '<=', date('Y-m-d H:i'))->where(function ($query) {
           $query->where('expire_at', '>', date('Y-m-d H:i'))->orWhereNull('expire_at');
        })->update([
            'active' => 1
        ]);

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn(['expire_at', 'activate_at']);
        });
    }
}