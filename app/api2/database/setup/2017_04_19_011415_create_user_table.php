<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('member_id')->unsigned()->index('member_id_idx');
			$table->string('username', 100)->unique('username_UNIQUE');
			$table->string('password', 100);
			$table->string('status', 20)->default('enabled');
			$table->dateTime('create_date')->nullable();
			$table->timestamp('edit_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('edit_name', 30)->nullable();
			$table->integer('login_fails')->nullable();
			$table->primary(['id','member_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
