<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebExclusiveColumnToPlaysheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('playsheets') && !Schema::hasColumn('playsheets', 'web_exclusive')) {
            Schema::table('playsheets', function (Blueprint $table) {
                $table->string('web_exclusive',1)->nullable()->default('0');
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
        if (Schema::hasTable('playsheets') && Schema::hasColumn('playsheets', 'web_exclusive')) {
            Schema::table('playsheets', function (Blueprint $table) {
                $table->dropColumn('web_exclusive');
            });
        }
    }
}
