<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreferredNameColumnToMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('membership') && !Schema::hasColumn('membership', 'preferred_name')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->string('preferred_name',100)->nullable()->default(NULL);
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
        if (Schema::hasTable('membership') && Schema::hasColumn('membership', 'preferred_name')) {
            Schema::table('membership', function (Blueprint $table) {
                $table->dropColumn('preferred_name');
            });
        }
    }
}
