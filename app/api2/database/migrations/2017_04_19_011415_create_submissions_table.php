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
			$table->boolean('format_id')->nullable();
			$table->text('catalog')->nullable();
			$table->integer('crtc')->nullable();
			$table->boolean('cancon')->nullable();
			$table->boolean('femcon')->nullable();
			$table->integer('local')->nullable();
			$table->boolean('playlist')->nullable();
			$table->boolean('compilation')->nullable();
			$table->boolean('digitized')->nullable();
			$table->text('status')->nullable();
			$table->text('artist')->nullable();
			$table->text('title')->nullable();
			$table->text('label')->nullable();
			$table->text('genre')->nullable();
			$table->text('tags')->nullable();
			$table->date('submitted')->nullable();
			$table->date('releasedate')->nullable();
			$table->integer('assignee')->unsigned()->nullable();
			$table->integer('reviewed')->nullable();
			$table->boolean('approved')->nullable();
			$table->text('description')->nullable();
			$table->text('location')->nullable();
			$table->text('email')->nullable();
			$table->bigInteger('songlist')->unsigned();
			$table->text('credit')->nullable();
			$table->text('art_url')->nullable();
			$table->text('review_comments', 16777215)->nullable();
			$table->string('staff_comment', 45)->nullable();
			$table->timestamps();
			$table->boolean('is_trashed')->nullable()->default(0);
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
