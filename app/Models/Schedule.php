<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    // public function course()
    // {
    //     return $this->belongsTo(Course::class);
    // }

    // public function day()
    // {
    //     return $this->belongsTo(Day::class);
    // }

    // public function announcements()
    // {
    //     return $this->hasMany(Announcement::class);
    // }

    // public function enrollments()
    // {
    //     return $this->hasMany(Enrollment::class);
    // }

    public function practicum()
    {
        return $this->belongsTo(Practicum::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
