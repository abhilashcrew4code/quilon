<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    public function permissionGroup()
    {
        return $this->belongsTo('App\Models\PermissionGroup', 'group_id');
    }
}
