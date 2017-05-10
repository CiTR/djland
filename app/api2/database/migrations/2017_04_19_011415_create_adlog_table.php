<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adlog', function(Blueprint $table)
		{
			//true refers to the autoincrementing session_status
			//TODO: determine if this can move to $table->increments()
			$table->integer('id', true);
			$table->integer('playsheet_id')->nullable();
			$table->smallInteger('num')->nullable();
			$table->string('time', 45)->nullable();
			$table->string('type', 45)->nullable();
			$table->text('name', 65535)->nullable();
			$table->boolean('played')->nullable();
			$table->integer('sam_id')->nullable();
			$table->integer('time_block')->nullable();
			$table->dateTime('create_date')->nullable();
			$table->dateTime('edit_date')->nullable();
			$table->index(['id','time_block'], 'unixtime');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adlog');
	}

}
