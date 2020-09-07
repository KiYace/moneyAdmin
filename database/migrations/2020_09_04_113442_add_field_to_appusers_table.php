<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToAppusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appusers', function (Blueprint $table) {
            $table->string('sec_name', 25)->nullable(false);
            $table->string('password');
            $table->string('token', 64)->unique();
            $table->string('push_token', 255)->nullable();
            $table->boolean('push_enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appusers', function (Blueprint $table) {
            $table->dropColumn('sec_name');
            $table->dropColumn('password');
            $table->dropColumn('token');
            $table->dropColumn('push_token');
            $table->dropColumn('push_enabled');
        });
    }
}
