<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserBackupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_backup', function(Blueprint $table)
		{
			$table->integer('userid')->unsigned()->default(0);
			$table->char('username', 20)->default(0);
			$table->char('password', 100)->default(0);
			$table->char('status', 20)->default(0);
			$table->dateTime('create_date')->nullable();
			$table->char('create_name', 30)->nullable();
			$table->dateTime('edit_date')->default('0000-00-00 00:00:00');
			$table->char('edit_name', 30)->nullable();
			$table->integer('login_fails')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_backup');
	}

}
