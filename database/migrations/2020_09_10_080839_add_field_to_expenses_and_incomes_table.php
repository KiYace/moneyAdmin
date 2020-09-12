<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToExpensesAndIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('transaction_type')->default('Расход')->after('bill_id');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->string('transaction_type')->default('Доход')->after('bill_id');
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
            $table->dropColumn('transaction_type');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
}
