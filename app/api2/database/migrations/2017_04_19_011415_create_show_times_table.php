<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShowTimesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_times', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->integer('show_id');
            $table->integer('start_day');
            $table->time('start_time');
            $table->integer('end_day');
            $table->time('end_time');
            $table->integer('alternating')->default(0);
            $table->index(['show_id','start_day','start_time','end_day','end_time','alternating'], 'show_times_key')->unique();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('show_times');
    }
}
