<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateApplicantsCourseId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            UPDATE applicants a
            INNER JOIN courses c ON a.courseName = c.name
                                 AND a.trainingOrganizerUniversity = c.trainingOrganizerUniversity
                                 AND a.organizerDeptOrInstituteOrCenter = c.organizerDeptOrInstituteOrCenter
            SET a.course_id = c.course_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
