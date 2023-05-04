<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscorderContributorColumnToMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('membership') && !Schema::hasColumn('membership', 'discorder_contributor')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->string('discorder_contributor',1)->nullable()->default('0');
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
        if (Schema::hasColumn('membership', 'discorder_contributor')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->dropColumn('discorder_contributor');
            });
        }
    }
}
