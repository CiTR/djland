<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFriendsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friends', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name');
			$table->text('address')->nullable();
			$table->string('website', 60)->nullable();
			$table->string('phone', 17)->nullable();
			$table->string('discount', 45)->nullable();
			$table->text('image')->nullable();
			$table->dateTime('created')->nullable();
			$table->dateTime('edited')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('friends');
	}

}
