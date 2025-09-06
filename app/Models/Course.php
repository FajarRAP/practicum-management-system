<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function practicums()
    {
        return $this->hasMany(Practicum::class);
    }
}
