<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicants';
    protected $primaryKey = 'id';

    function course() {
        return $this->belongsTo('App\Course', 'course_id', 'course_id');
    }
    public function batch()
    {
        return $this->belongsTo('App\Batch', 'batch_id', 'batch_id');
    }
}
