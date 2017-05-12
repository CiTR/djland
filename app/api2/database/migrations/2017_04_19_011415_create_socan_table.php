<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocanTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socan', function (Blueprint $table) {
            $table->increments('idSocan');
            $table->date('socanStart');
            $table->date('socanEnd');
            $table->timestamps();
            $table->unique(['idSocan','socanStart','socanEnd']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('socan');
    }
}
