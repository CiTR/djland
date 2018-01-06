<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmissionsRejectedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submissions_rejected', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('email')->nullable();
			$table->text('artist')->nullable();
			$table->text('title')->nullable();
			$table->date('submitted')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('submissions_rejected'))
		{
			Schema::drop('submissions_rejected');
		}
	}

}
