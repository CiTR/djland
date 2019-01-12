<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_id')->unsigned();
            $table->datetime('start_datetime')->nullable();
            $table->datetime('end_datetime')->nullable();
            $table->string('host')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('spokenword_duration')->unsigned()->default(0);
            $table->string('language')->nullable();
            $table->string('broadcast_type')->nullable();
            $table->boolean('is_published')->default(1);
            $table->boolean('is_web_exclusive')->default(0);

            $table->timestamps();

            $table->foreign('show_id')
                    ->references('id')
                    ->on('shows')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
