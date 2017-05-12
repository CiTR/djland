<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmissionSongsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_songs', function (Blueprint $table) {
            $table->increments('song_id');
            $table->integer('submission_id')->unsigned()->nullable()->index('fk_submission_songs_1_idx');
            $table->string('artist')->nullable();
            $table->string('album_artist')->nullable();
            $table->string('album_title')->nullable();
            $table->string('song_title')->nullable();
            $table->string('credit', 45)->nullable();
            $table->smallInteger('track_num')->nullable()->default(0);
            $table->smallInteger('tracks_total')->nullable()->default(0);
            $table->string('genre')->nullable();
            $table->boolean('s/t', 1)->nullable()->default(0);
            $table->boolean('v/a', 1)->nullable()->default(0);
            $table->boolean('compilation', 1)->nullable()->default(0);
            $table->string('composer')->nullable();
            $table->boolean('crtc')->nullable();
            $table->date('year')->nullable();
            $table->integer('length')->unsigned()->nullable();
            $table->mediumText('file_location')->nullable();
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
        Schema::drop('submission_songs');
    }
}
