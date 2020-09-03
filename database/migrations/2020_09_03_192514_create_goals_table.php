<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('goal_name', 15)->nullable(false);
            $table->string('goal_description', 150)->nullable();
            $table->float('goal_sum', 30, 2)->nullable(false);
            $table->float('goal_balance', 30, 2)->nullable();
            $table->string('goal_currency', 3)->default('RUB');
            $table->integer('goal_reminder')->nullable();
            $table->integer('user_id')->nullable(false);
            $table->date('goal_finish')->nullable(false);
            $table->integer('goal_important');
            $table->boolean('goal_active');

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
        Schema::dropIfExists('goals');
    }
}
