<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLibraryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('library', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->boolean('format_id')->default(8);
			$table->text('catalog')->nullable();
			$table->integer('crtc')->nullable();
			$table->boolean('cancon')->default(0);
			$table->boolean('femcon')->default(0);
			$table->integer('local')->unsigned()->default(0);
			$table->boolean('playlist')->default(0);
			$table->boolean('compilation')->default(0);
			$table->boolean('digitized')->default(0);
			$table->text('status')->nullable();
			$table->text('artist')->nullable();
			$table->text('title')->nullable();
			$table->text('label')->nullable();
			$table->text('genre')->nullable();
			$table->date('added')->nullable();
			$table->date('modified')->nullable();
			$table->text('description')->nullable();
			$table->text('email')->nullable();
			$table->string('art_url')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('library');
	}

}
