<?php

namespace App\Models\CourrierManagments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImputationCourriersAgentsModels extends Model
{
    use HasFactory;


    protected $fileable = [
        'employee_matricule',
        'courrier_ref',
        'date_execution',
        'new_actions',
        'slug',
    ];
}
