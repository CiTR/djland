<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMemberResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//TODO: determine if this table has any use
		Schema::create('member_resources', function(Blueprint $table)
		{
			$table->integer('index')->primary();
			$table->text('blurb')->nullable();
			$table->text('link')->nullable();
			$table->string('type', 45)->nullable()->default('general');
			$table->dateTime('CREATED_AT')->nullable();
			$table->dateTime('UPDATED_AT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('member_resources'))
		{
			Schema::drop('member_resources');
		}
	}

}
