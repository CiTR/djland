<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned()->index('member_id_idx');
            $table->string('username', 100)->unique('username_UNIQUE');
            $table->string('password', 100);
            $table->string('status', 20)->default('enabled');
            $table->integer('login_fails')->nullable();
            $table->string('edit_name', 30)->nullable();
            $table->dateTime('create_date')->nullable();
            $table->timestamp('edit_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index(['id','member_id'])->unique();
            $table->foreign('member_id')->references('id')->on('membership')->onDelete('cascade')->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		if(Schema::hasTable('user'))
		{
        	Schema::drop('user');
		}
    }
}
