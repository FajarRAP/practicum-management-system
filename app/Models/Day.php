<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $guarded = [];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
