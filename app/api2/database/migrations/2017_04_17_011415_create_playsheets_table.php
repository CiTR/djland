<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaysheetsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playsheets', function (Blueprint $table) {
            $table->bigIncrements('id', true)->unsigned();
            $table->integer('show_id')->unsigned()->nullable()->index('playsheet_show_id_idx');
            $table->text('host')->nullable();
            $table->integer('host_id')->unsigned()->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->time('end')->nullable();
            $table->text('create_name')->nullable();
            $table->text('edit_name')->nullable();
            $table->text('title')->nullable();
            $table->mediumText('summary')->nullable();
            $table->integer('spokenword_duration')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('unix_time')->nullable();
            $table->tinyInteger('star')->nullable();
            $table->integer('crtc')->nullable();
            $table->text('lang', 65535)->nullable();
            $table->string('type', 45)->nullable();
            $table->text('show_name')->nullable();
            $table->string('socan', 1)->nullable();
            $table->dateTime('create_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('edit_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index(['id','edit_date','status'], 'recent');
            $table->foreign('show_id', 'playsheet_show_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		if(Schema::hasTable('playsheets'))
		{
			Schema::drop('playsheets');
		}
    }
}
