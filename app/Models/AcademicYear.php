<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $guarded = [];

    public function practicums()
    {
        return $this->hasMany(Practicum::class);
    }
}
