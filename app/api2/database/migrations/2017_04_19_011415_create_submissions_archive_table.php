<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmissionsArchiveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submissions_archive', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('contact')->nullable();
			$table->text('catalog')->nullable();
			$table->text('artist')->nullable();
			$table->text('title')->nullable();
			$table->date('submitted')->nullable();
			$table->tinyInteger('format_id')->nullable();
			$table->boolean('cancon')->nullable();
			$table->boolean('femcon')->nullable();
			$table->boolean('local')->nullable();
			$table->text('label')->nullable();
			$table->text('review_comments')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('submissions_archive'))
		{
			Schema::drop('submissions_archive');
		}
	}

}
