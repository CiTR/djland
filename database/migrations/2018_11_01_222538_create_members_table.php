<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id'); // Foreign key to users
            $table->string('last_name', 90); // Formerly lastname
            $table->string('first_name', 90); // Formerly firstname
            $table->string('preferred_name')->nullable();
            $table->boolean('is_canadian_citizen')->default(0); // Formerly canadian_citizen
            $table->string('address', 55)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('province', 4)->nullable();
            $table->string('postal_code', 6)->nullable(); // Formerly postalcode
            $table->unsignedInteger('membership_type_id'); // Foreign key to member_types. Formerly member_type
            $table->boolean('is_new')->default(0);
            $table->boolean('is_alumni')->default(0); // Formerly alumni
            $table->boolean('is_approved')->default(0); // Formerly status
            $table->boolean('is_discorder_contributor')->default(0); // Formerly discorder_contributor
            $table->unsignedInteger('member_year_id'); // Foreign key to member_years. Formerly since
            $table->string('faculty')->nullable();
            $table->unsignedTinyInteger('school_year')->default(0); // Formerly schoolyear
            $table->string('student_no', 100)->nullable();
            $table->boolean('course_integrate')->default(0); // Formerly integrate. Signifies intent to include in school course
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->text('comments')->nullable();
            $table->text('about')->nullable();
            $table->text('skills')->nullable();
            $table->text('exposure')->nullable();
            $table->boolean('taken_station_tour')->default(0); // Formerly station_tour
            $table->boolean('taken_tech_training')->default(0); // Formerly technical_training
            $table->boolean('taken_prog_training')->default(0); // Formerly programming_training
            $table->boolean('taken_prod_training')->default(0); // Formerly production_training
            $table->boolean('taken_spoken_training')->default(0); // Formerly spoken_word_training

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
