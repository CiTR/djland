<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDjlandOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('djland_options', function(Blueprint $table)
		{
			$table->increments('index');
			$table->text('djland_option');
			$table->text('value', 65535);
			$table->dateTime('CREATED_AT')->nullable();
			$table->dateTime('UPDATED_AT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('djland_options');
	}

}
