<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToSubgenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subgenres', function (Blueprint $table) {
            $table->foreign('parent_genre_id')
                ->references('id')->on('genres')->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subgenres', function (Blueprint $table) {
            $table->dropForeign(['parent_genre_id']);
        });
    }
}
