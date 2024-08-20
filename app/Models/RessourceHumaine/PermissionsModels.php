<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'permission_ref',
        'customer_id',
        'customer_name',
        'fonction',
        'service',
        'motif',
        'duree_permission',
        'date_demande',
        'new_actions',
        'permission_file',
        'status_on',
        'status_off',
        'slug',
    ];
}
