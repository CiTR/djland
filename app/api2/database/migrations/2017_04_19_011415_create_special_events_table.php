<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpecialEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('special_events', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 455)->nullable();
			$table->integer('show_id')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('start')->nullable();
			$table->integer('end')->nullable();
			$table->string('image', 455)->nullable();
			$table->string('url', 455)->nullable();
			$table->dateTime('edited')->nullable();
			$table->dateTime('created')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('special_events'))
		{
			Schema::drop('special_events');
		}

	}

}
