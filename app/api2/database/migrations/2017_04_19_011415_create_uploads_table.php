<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uploads', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('file_name');
			$table->string('file_type', 45);
			$table->string('category', 45);
			$table->text('path')->nullable();
			$table->text('size')->nullable();
			$table->text('description')->nullable();
			$table->text('url')->nullable();
			$table->integer('relation_id')->nullable();
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
		Schema::drop('uploads');
	}

}
