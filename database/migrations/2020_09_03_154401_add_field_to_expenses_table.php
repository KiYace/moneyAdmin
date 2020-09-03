<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->json('tags_id')->nullable();
            $table->string('notice', 100)->nullable();
            $table->integer('repeat')->nullable();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->json('tags_id')->nullable();
            $table->string('notice', 100)->nullable();
            $table->integer('repeat')->nullable();
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
            $table->dropColumn('tags_id');
            $table->dropColumn('notice');
            $table->dropColumn('repeat');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('tags_id');
            $table->dropColumn('notice');
            $table->dropColumn('repeat');
        });
    }
}
