<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('debt_name', 15)->nullable(false);
            $table->string('debt_desc', 150)->nullable();
            $table->integer('debt_type')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('bill_id')->nullable(false);
            $table->float('debt_sum', 20, 2)->nullable(false);
            $table->string('debt_currency', 3)->default('RUB');
            $table->date('debt_finish')->nullable();
            $table->integer('debt_reminder')->nullable();
            $table->integer('debt_important')->nullable(false);
            $table->boolean('debt_active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts');
    }
}
