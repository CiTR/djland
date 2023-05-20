<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLibrarySongsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('library_songs', function(Blueprint $table)
		{
			$table->foreign('library_id', 'fk_library_songs_1')->references('id')->on('library')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('library_songs', function(Blueprint $table)
		{
			$table->dropForeign('fk_library_songs_1');
		});
	}

}
