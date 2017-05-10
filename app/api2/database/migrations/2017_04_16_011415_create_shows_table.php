<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShowsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shows', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name')->nullable();
			$table->text('host')->nullable();
			$table->text('primary_genre_tags', 65535)->nullable();
			$table->text('secondary_genre_tags', 65535)->nullable();
			$table->boolean('weekday')->default(0);
			$table->time('start_time')->default('00:00:00');
			$table->time('end_time')->default('00:00:00');
			$table->boolean('pl_req')->default(0);
			$table->boolean('cc_20_req')->default(35);
			$table->boolean('cc_30_req')->default(12);
			$table->boolean('indy_req')->default(0);
			$table->boolean('fem_req')->default(0);
            //The last_show column appears to be unused and deprecated
            //default changed from 0000-00-00 00:00:00 to NULL, but probably won't make a difference
			$table->dateTime('last_show')->default(NULL);
			$table->text('create_name');
			$table->text('edit_name');
			$table->boolean('active')->default(1);
			$table->integer('crtc_default')->default(20);
			$table->text('lang_default')->nullable();
			$table->text('website')->nullable();
			$table->text('rss')->nullable();
			$table->text('show_desc', 65535)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->text('image')->nullable();
			$table->text('sponsor_name')->nullable();
			$table->text('sponsor_url')->nullable();
			$table->string('showtype', 45)->nullable()->default('Live');
			$table->string('explicit', 1)->nullable()->default('0');
			$table->text('alerts', 65535)->nullable();
			$table->text('podcast_xml')->nullable();
			$table->string('podcast_slug', 45)->nullable();
			$table->text('podcast_title')->nullable();
			$table->text('podcast_subtitle')->nullable();
			$table->text('podcast_summary', 65535)->nullable();
			$table->text('podcast_author')->nullable();
            $table->dateTime('create_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edit_date')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shows');
	}

}
