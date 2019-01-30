<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned();
            $table->integer('order')->unsigned()->default(0);
            $table->string('artist');
            $table->string('title');
            $table->string('album')->nullable();
            $table->string('language');
            $table->datetime('start_datetime')->nullable();
            $table->integer('duration')->unsigned()->default(0);
            $table->integer('song_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episode_items');
    }
}
