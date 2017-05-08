<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePodcastEpisodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('podcast_episodes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->bigInteger('playsheet_id')->unsigned();
			$table->integer('show_id')->nullable();
			$table->text('image')->nullable();
			$table->text('title', 65535)->nullable();
			$table->text('subtitle', 65535)->nullable();
			$table->text('summary', 65535)->nullable();
			$table->dateTime('date')->nullable();
			$table->text('iso_date', 65535)->nullable();
			$table->text('url', 65535)->nullable();
			$table->integer('length')->nullable();
			$table->text('author', 65535)->nullable();
			$table->boolean('active')->nullable()->default(0);
			$table->integer('duration')->nullable()->default(0);
			$table->timestamp('UPDATED_AT')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('CREATED_AT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('podcast_episodes');
	}

}
