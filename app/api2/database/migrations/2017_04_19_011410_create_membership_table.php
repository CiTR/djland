<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lastname', 90);
            $table->string('firstname', 90);
            $table->string('canadian_citizen', 1)->comment('citizen, immigrant, visitor');
            $table->string('address', 55);
            $table->string('city', 45)->default('Vancouver');
            $table->string('province', 4)->default('BC');
            $table->string('postalcode', 6);
            $table->string('member_type', 9)->comment('student, community, alumni, lifetime');
            $table->string('is_new', 1)->default('0');
            $table->string('alumni', 1)->default('0');
            //TODO:make this date default dynamic - defaults to year the system was installed
            $table->string('since', 9)->default('2014/2015');
            $table->string('faculty', 22)->nullable();
            $table->string('schoolyear', 2)->nullable();
            $table->string('student_no', 8)->nullable()->unique('student_no_UNIQUE')->comment('Student Number');
            $table->string('integrate', 1)->default('0');
            $table->string('has_show', 1)->default('0');
            $table->string('show_name', 100)->nullable();
            $table->string('primary_phone', 10);
            $table->string('secondary_phone', 10)->nullable();
            $table->text('email');
            $table->text('comments')->nullable();
            $table->text('about', 65535)->nullable();
            $table->text('skills', 65535)->nullable();
            $table->string('status', 10)->default('pending');
            $table->text('exposure', 65535)->nullable();
            $table->string('station_tour', 1)->nullable()->default('0');
            $table->string('technical_training', 1)->nullable()->default('0');
            $table->string('programming_training', 1)->nullable()->default('0');
            $table->string('production_training', 1)->nullable()->default('0');
            $table->string('spoken_word_training', 1)->nullable()->default('0');
            $table->dateTime('create_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('edit_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		if(Schema::hasTable('membership'))
		{
		    Schema::drop('membership');
		}
    }
}
