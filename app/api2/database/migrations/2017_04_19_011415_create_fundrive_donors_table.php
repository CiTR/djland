<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFundriveDonorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fundrive_donors', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('status', 45)->nullable()->default('unsaved');
			$table->string('donation_amount', 10)->nullable();
			$table->string('swag', 1)->nullable();
			$table->string('tax_receipt', 1)->nullable();
			$table->text('show_inspired')->nullable();
			$table->string('prize', 45)->nullable();
			$table->string('firstname', 45)->nullable();
			$table->string('lastname', 45)->nullable();
			$table->string('address', 90)->nullable();
			$table->string('city', 45)->nullable();
			$table->string('province', 4)->nullable();
			$table->string('postalcode', 6)->nullable();
			$table->string('country', 60)->nullable();
			$table->string('phonenumber', 12)->nullable();
			$table->string('email', 45)->nullable();
			$table->string('payment_method', 45)->nullable();
			$table->string('mail_yes', 1)->nullable();
			$table->string('postage_paid', 30)->nullable();
			$table->string('recv_updates_citr', 1)->nullable();
			$table->string('recv_updates_alumni', 1)->nullable();
			$table->string('donor_recognition_name', 45)->nullable();
			$table->string('LP_yes', 1)->nullable();
			$table->string('LP_amount', 5)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->string('paid', 1)->nullable();
			$table->string('prize_picked_up', 1)->nullable();
			$table->dateTime('UPDATED_AT')->nullable();
			$table->dateTime('CREATED_AT')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if(Schema::hasTable('fundrive_donors'))
		{
			Schema::drop('fundrive_donors');
		}
	}

}
