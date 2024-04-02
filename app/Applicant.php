<?php

namespace App;

// use App\Course;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicants';
    protected $primaryKey = 'id';

    function course() {
        return $this->hasMany('App\Course', 'course_id', 'course_id');
    }
}
