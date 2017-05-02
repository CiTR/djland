<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShowTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('show_times', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('show_id');
			$table->integer('start_day');
			$table->time('start_time');
			$table->integer('end_day');
			$table->time('end_time');
			$table->integer('alternating')->default(0);
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
