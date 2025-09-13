<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function practicum()
    {
        return $this->belongsTo(Practicum::class);
    }
}
