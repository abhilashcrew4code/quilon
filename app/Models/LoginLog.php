<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $dates = ['last_active_at', 'logout_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
