<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->tinyInteger('format_id')->nullable();
			$table->text('catalog')->nullable();
			$table->integer('crtc')->nullable();
			$table->boolean('cancon')->nullable();
			$table->boolean('femcon')->nullable();
			$table->integer('local')->nullable();
			$table->boolean('playlist')->nullable();
			$table->boolean('compilation')->nullable();
			$table->boolean('digitized')->nullable();
			$table->text('status')->nullable();
			$table->boolean('is_trashed')->nullable()->default(0);
			$table->text('artist')->nullable();
			$table->text('title')->nullable();
			$table->text('label')->nullable();
			$table->text('genre')->nullable();
			$table->text('tags')->nullable();
			$table->date('submitted')->nullable();
			$table->date('releasedate')->nullable();
			//TODO: forgien key for assignee + reviewed in both model and Schema
			// levels - on delete/update no action of course
			$table->integer('assignee')->unsigned()->nullable();
			$table->integer('reviewed')->nullable();
			$table->boolean('approved')->nullable();
			$table->text('description')->nullable();
			$table->text('location')->nullable();
			$table->text('email')->nullable();
			$table->text('credit')->nullable();
			$table->text('art_url')->nullable();
			$table->mediumText('review_comments')->nullable();
			$table->string('staff_comment', 255)->nullable();
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
		Schema::drop('submissions');
	}

}
