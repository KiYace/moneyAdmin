<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToExpensesAndIcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->integer('bill_id')->nullable()->change();
            $table->integer('goal_id')->nullable()->after('bill_id');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->integer('bill_id')->nullable()->change();
            $table->integer('goal_id')->nullable()->after('bill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('goal_id');
            $table->integer('bill_id')->nullable(false)->change();
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('goal_id');
            $table->integer('bill_id')->nullable(false)->change();
        });
    }
}
