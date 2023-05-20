<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMusicAffairsCollectiveAndUbcAffairsCollectiveColumnsToMembershipYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_years', function (Blueprint $table) {
            $table->string('music_affairs_collective',1)->nullable()->default('0');
            $table->string('ubc_affairs_collective',1)->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('membership_years', 'music_affairs_collective')) {
            Schema::table('membership_years', function (Blueprint $table) {
                $table->dropColumn('music_affairs_collective');
            });
        }

        if (Schema::hasColumn('membership_years', 'ubc_affairs_collective')) {
            Schema::table('membership_years', function (Blueprint $table) {
                $table->dropColumn('ubc_affairs_collective');
            });
        }
    }
}
