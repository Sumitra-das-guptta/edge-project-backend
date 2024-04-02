<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'course_id';

    function applicants() {
        return $this->hasMany('App\Applicant', 'course_id', 'course_id');
    }
}
