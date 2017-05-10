<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryEdits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_edits', function (Blueprint $table) {
            $table->increments('id')->primary();
            $table->tinyInteger('format_id')->nullable();
            $table->tinyInteger('old_format_id')->nullable();
            $able->text('catalog')->nullable();
            $able->text('old_catalog')->nullable();
            $table->boolean('cancon')->nullable();
            $table->boolean('old_cancon')->nullable();
            $table->boolean('femcon')->nullable();
            $table->boolean('old_femcon')->nullable();
            $table->boolean('local')->nullable();
            $table->boolean('old_local')->nullable();
            $table->boolean('playlist')->nullable();
            $table->boolean('old_playlist')->nullable();
            $table->boolean('compilation')->nullable();
            $table->boolean('old_compilation')->nullable();
            $table->boolean('digitized')->nullable();
            $table->boolean('old_digitized')->nullable();
            $table->text('status')->nullable();
            $table->text('old_status')->nullable();
            $table->text('artist')->nullable();
            $table->text('old_artist')->nullable();
            $table->text('title')->nullable();
            $table->text('old_title')->nullable();
            $table->text('label')->nullable();
            $table->text('old_label')->nullable();
            $table->text('genre')->nullable();
            $table->text('old_genre')->nullable();
            $table->integer('library_id')->nullable();
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
        Schema::drop('library_edits');
    }
}
