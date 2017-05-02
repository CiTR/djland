<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_members', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index('userid_idx');
			$table->string('operator', 1)->nullable()->default('0');
			$table->string('administrator', 1)->nullable()->default('0');
			$table->string('staff', 1)->nullable()->default('0');
			$table->string('workstudy', 1)->nullable()->default('0');
			$table->string('volunteer_leader', 1)->nullable()->default('0');
			$table->string('volunteer', 45)->nullable()->default('0');
			$table->string('dj', 1)->nullable()->default('0');
			$table->string('member', 1)->nullable()->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('group_members');
	}

}
