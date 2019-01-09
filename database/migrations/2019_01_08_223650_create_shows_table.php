<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('host')->nullable();
            $table->tinyInteger('weekday')->unsigned();
            $table->time('start_time');
            $table->time('end_time');
            $table->datetime('last_show')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_explicit')->default(0);
            $table->string('website')->nullable();
            $table->string('rss')->nullable();
            $table->string('podcast_xml')->nullable();

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
        Schema::dropIfExists('shows');
    }
}
