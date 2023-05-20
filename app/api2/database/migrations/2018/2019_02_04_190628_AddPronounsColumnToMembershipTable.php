<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPronounsColumnToMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('membership') && !Schema::hasColumn('membership', 'pronouns')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->string('pronouns',255)->nullable()->default(NULL);
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
        if (Schema::hasTable('membership') && Schema::hasColumn('membership', 'pronouns')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->dropColumn('pronouns');
            });
        }
    }
}
