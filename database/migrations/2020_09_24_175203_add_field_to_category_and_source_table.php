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
            $table->integer('debt_id')->nullable()->after('goal_id');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->integer('debt_id')->nullable()->after('goal_id');
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
            $table->dropColumn('debt_id');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('debt_id');
        });
    }
}
