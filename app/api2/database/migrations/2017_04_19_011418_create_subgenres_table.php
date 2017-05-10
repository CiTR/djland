<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubgenresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subgenres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('subgenre');
			$table->integer('parent_genre_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->foreign('parent_genre_id','fk_parent_genre_id')->references('id')->on('genre')->onDelete('no action')->onUpdate('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subgenres');
	}

}
