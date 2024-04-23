<?php

namespace App;

use App\Course;
use App\Applicant;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batches';
    protected $primaryKey = 'batch_id';

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'course_id');
    }

    public function applicants()
    {
        return $this->hasMany('App\Applicant', 'batch_id', 'batch_id');
    }
}
