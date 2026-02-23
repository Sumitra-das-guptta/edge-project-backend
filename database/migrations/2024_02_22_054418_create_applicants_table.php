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
            $table->string('courseName', 255);
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
            $table->string('image_path', 255)->nullable();
            $table->text('presentAddressRoadNo');
            $table->string('presentAddressThanaName', 255);
            $table->string('presentAddressDistrictName', 255);
            $table->string('presentAddressDivisionName', 255);
            $table->text('permanentAddressRoadNo');
            $table->string('permanentAddressThanaName', 255);
            $table->string('permanentAddressDistrictName', 255);
            $table->string('permanentAddressDivisionName', 255);
            $table->string('mobileNumber', 100);
            $table->string('gurdianMobileNumber', 100);
            $table->string('levelOfEducation', 100);
            $table->string('subjectName', 100);
            $table->string('universityName', 100);
            $table->string('departmentName', 100);
            $table->text('trainingLocation');
            $table->string('linkedinProfile', 255);
            $table->string('projectRepository', 255);
            $table->string('freelancingProfile', 255);
            $table->boolean('status')->default(1);
            $table->string('identityNo', 100)->unique();
            $table->string('email', 100)->unique();
            
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
