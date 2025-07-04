<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurityQuestion extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function securityQuestion()
    {
        return $this->belongsTo(SecurityQuestion::class);
    }
}
