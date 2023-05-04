<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//TODO: This table currently isn't being used but would be nice to have
		//because normalization is a good thing
		Schema::create('membership_status', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('name');
			$table->integer('sort')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('membership_status')){
			Schema::drop('membership_status');
		}
	}

}
