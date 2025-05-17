<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
