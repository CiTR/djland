<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSubmissionSongsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('submission_songs', function(Blueprint $table)
		{
			//TODO: check cascade behavior with rest of code to see if no action
			// is what we want
			$table->foreign('submission_id', 'fk_submission_songs_1')->references('id')->on('submissions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('submission_songs', function(Blueprint $table)
		{
			$table->dropForeign('fk_submission_songs_1');
		});
	}

}
