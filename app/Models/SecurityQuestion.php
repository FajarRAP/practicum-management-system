<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
    protected $guarded = [];

    public function userSecurityQuestions()
    {
        return $this->hasOne(UserSecurityQuestion::class);
    }
}
