<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMemberShowTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('member_show', function(Blueprint $table)
		{
			//TODO: check if this table needs foreign keys (it's a pivot table)
			$table->increments('id');
			$table->integer('member_id');
			$table->integer('show_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('member_show');
	}

}
