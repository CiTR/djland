<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoglevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loglevel', function (Blueprint $table) {
            $table->increments('loglevel_id');
            $table->string('level')->unique();
        });
        //Add foreign keys to link tables
        Schema::table('log', function (Blueprint $table) {
            $table->foreign('log_level', 'fk_log_1')->references('loglevel_id')->on('loglevel')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loglevel');
        Schema::table('log', function (Blueprint $table) {
            $table->dropForeign('fk_log_1');
        });
    }
}
