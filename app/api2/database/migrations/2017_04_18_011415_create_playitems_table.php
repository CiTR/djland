<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayitemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('playitems', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('show_id')->unsigned()->nullable()->index('playitem_show_id_idx');
			$table->bigInteger('playsheet_id')->unsigned()->nullable()->index('playitem_playsheet_id_idx');
			$table->bigInteger('song_id')->unsigned()->nullable();
			$table->tinyInteger('format_id')->nullable();
			$table->boolean('is_playlist')->nullable()->default(0);
			$table->boolean('is_canadian')->nullable()->default(0);
			$table->boolean('is_yourown')->nullable()->default(0);
			$table->boolean('is_indy')->nullable()->default(0);
			$table->boolean('is_fem')->nullable()->default(0);
			$table->date('show_date')->nullable();
			$table->text('duration')->nullable();
			$table->boolean('is_theme')->nullable();
			$table->boolean('is_background')->nullable();
			$table->integer('crtc_category')->nullable()->default(20);
			$table->text('lang')->nullable();
			$table->integer('is_part')->default(0);
			$table->integer('is_inst')->default(0);
			$table->integer('is_hit')->default(0);
			$table->tinyInteger('insert_song_start_hour')->nullable()->default(0);
			$table->tinyInteger('insert_song_start_minute')->nullable()->default(0);
			$table->tinyInteger('insert_song_length_minute')->nullable()->default(0);
			$table->tinyInteger('insert_song_length_second')->nullable()->default(0);
			$table->string('artist', 80)->nullable();
			$table->string('song', 80)->nullable();
			$table->string('album', 80)->nullable();
			$table->string('composer', 80)->nullable();
            $table->foreign('playsheet_id', 'playitem_playsheet_id')->references('id')->on('playsheets')->onUpdate('cascade')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('playitems'))
		{
			Schema::drop('playitems');
		}
	}

}
