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
                $table->boolean('web_exclusive');
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
