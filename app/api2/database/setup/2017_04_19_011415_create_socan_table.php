<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('socan', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('idSocan_UNIQUE');
			$table->date('socanStart')->nullable();
			$table->date('socanEnd')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('socan');
	}

}
