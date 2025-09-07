<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practicum extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
