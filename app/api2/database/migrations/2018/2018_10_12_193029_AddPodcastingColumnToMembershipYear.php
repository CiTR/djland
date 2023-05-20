<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPodcastingColumnToMembershipYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('membership_years') && !Schema::hasColumn('membership_years', 'podcasting')) {
            Schema::table('membership_years', function (Blueprint $table) {
                $table->string('podcasting',1)->nullable()->default('0');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('membership_years') && Schema::hasColumn('membership_years', 'podcasting')) {
            Schema::table('membership_years', function (Blueprint $table) {
                $table->dropColumn('podcasting');
            });
        }
    }
}
