<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaysheetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('playsheets', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('show_id')->unsigned()->nullable()->index('playsheet_show_id_idx');
			$table->text('host')->nullable();
			$table->integer('host_id')->unsigned()->nullable();
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->time('end')->nullable();
			$table->dateTime('create_date')->nullable();
			$table->text('create_name')->nullable();
			$table->dateTime('edit_date');
			$table->text('title')->nullable();
			$table->text('edit_name')->nullable();
			$table->text('summary', 16777215)->nullable();
			$table->integer('spokenword_duration')->nullable();
			$table->boolean('status')->nullable();
			$table->integer('unix_time')->nullable();
			$table->boolean('star')->nullable();
			$table->integer('crtc')->nullable();
			$table->text('lang', 65535)->nullable();
			$table->string('type', 45)->nullable();
			$table->text('show_name')->nullable();
			$table->string('socan', 1)->nullable();
			$table->index(['id','edit_date','status'], 'recent');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('playsheets');
	}

}
