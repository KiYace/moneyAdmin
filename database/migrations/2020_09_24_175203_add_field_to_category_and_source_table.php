<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToCategoryAndSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('display')->nullable()->deafult(true);
        });
        Schema::table('sources', function (Blueprint $table) {
            $table->boolean('display')->nullable()->deafult(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('display');
        });
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn('display');
        });
    }
}
