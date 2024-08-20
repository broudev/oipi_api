<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableAgentsModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'employee_matricule',
        'employee_service_id',
        'type_responsable_agent',
        'slug'
    ];
}
