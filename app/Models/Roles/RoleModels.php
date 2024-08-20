<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'role',
        'slug'
    ];
}
