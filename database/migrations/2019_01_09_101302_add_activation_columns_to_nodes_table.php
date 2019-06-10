<?php

use Illuminate\Database\Query\Builder;
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

        if (Schema::getConnection()->getDriverName() === 'sqlsrv') {
            $this->dropActiveColumnDefaultConstraintForSqlServer();
        }

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
        Schema::table('nodes', function (Blueprint $table) {
            if (!Schema::hasColumn('nodes', 'active')) {
                $table->tinyInteger('active')->default(0);
            }
            $table->index('active');
        });

        DB::table('nodes')->where('activate_at', '<=', date('Y-m-d H:i'))->where(function (Builder $query) {
            $query->where('expire_at', '>', date('Y-m-d H:i'))->orWhereNull('expire_at');
        })->update([
            'active' => 1
        ]);

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn(['expire_at', 'activate_at']);
        });
    }

    /**
     * Finds the random name of default value constraint autogenerated by SQL Server for active column and drops it.
     */
    protected function dropActiveColumnDefaultConstraintForSqlServer()
    {
        $defaultConstraint = DB::selectOne("SELECT OBJECT_NAME([default_object_id]) AS name FROM SYS.COLUMNS WHERE [object_id] = OBJECT_ID('[dbo].[nodes]') AND [name] = 'active'");
        $constraint = new \Doctrine\DBAL\Schema\Index($defaultConstraint->name, ['active']);

        Schema::getConnection()->getDoctrineSchemaManager()->dropConstraint($constraint, 'nodes');
    }
}