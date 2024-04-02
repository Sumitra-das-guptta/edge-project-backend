<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('trainingOrganizerUniversity');
            $table->string('organizerDeptOrInstituteOrCenter');
            $table->string('candidateName', 100);
            $table->string('fatherName', 100);
            $table->string('motherName', 100);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('religion', 100);
            $table->date('birthDate');
            $table->string('nationality', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
