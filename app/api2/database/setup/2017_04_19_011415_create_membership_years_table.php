<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipYearsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('membership_years', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('member_id')->unsigned()->index('member_id_idx');
			$table->string('membership_year', 9);
			$table->string('paid', 1)->default('0');
			$table->string('sports', 1)->nullable()->default('0');
			$table->string('news', 1)->nullable()->default('0');
			$table->string('arts', 1)->nullable()->default('0');
			$table->string('music', 1)->nullable()->default('0');
			$table->string('show_hosting', 1)->nullable()->default('0');
			$table->string('live_broadcast', 1)->nullable()->default('0');
			$table->string('tech', 1)->nullable()->default('0');
			$table->string('programming_committee', 1)->nullable()->default('0');
			$table->string('ads_psa', 1)->nullable()->default('0');
			$table->string('promotions_outreach', 1)->nullable()->default('0');
			$table->string('discorder_illustrate', 1)->nullable()->default('0');
			$table->string('discorder_write', 1)->nullable()->default('0');
			$table->string('digital_library', 1)->nullable()->default('0');
			$table->string('photography', 1)->nullable()->default('0');
			$table->string('tabling', 45)->nullable()->default('0');
			$table->string('dj', 1)->nullable()->default('0');
			$table->string('other', 45)->nullable();
			$table->timestamp('create_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('edit_date')->nullable();
			$table->string('womens_collective', 16)->nullable()->default('0');
			$table->string('indigenous_collective', 16)->nullable()->default('0');
			$table->string('accessibility_collective', 16)->nullable()->default('0');
			$table->primary(['id','member_id','membership_year']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('membership_years');
	}

}
