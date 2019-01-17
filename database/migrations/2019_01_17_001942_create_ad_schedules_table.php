<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->smallInteger('minutes_into_show')->nullable();
            $table->tinyInteger('minutes_past_hour')->unsigned()->nullable();
            $table->time('time_start')->default('00:00:00');
            $table->time('time_end')->default('23:59:59');
            $table->datetime('active_datetime_start');
            $table->datetime('active_datetime_end')->nullable();
            $table->boolean('is_only_specific_shows')->default(false);
            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER tr_active_datetime_start_default BEFORE INSERT ON `ad_schedules`
            FOR EACH ROW SET NEW.active_datetime_start = IFNULL(NEW.active_datetime_start, NOW());
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('
            DROP TRIGGER tr_active_datetime_start_default;
        ');
        Schema::dropIfExists('ad_schedules');
    }
}
