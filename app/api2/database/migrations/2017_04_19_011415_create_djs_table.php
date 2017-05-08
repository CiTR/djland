<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDjsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('djs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('name', 65535);
			$table->text('day', 65535);
			$table->text('time', 65535);
			$table->text('dj', 65535);
			$table->text('desc', 65535);
			$table->text('image', 65535);
			$table->text('email', 65535);
			$table->text('website', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('djs');
	}

}
