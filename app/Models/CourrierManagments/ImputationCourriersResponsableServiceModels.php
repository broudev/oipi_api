<?php

namespace App\Models\CourrierManagments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImputationCourriersResponsableServiceModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'employee_matricule',
        'courrier_ref',
        'date_imputation',
        'date_execution',
        'responsable_new_actions',
        'slug',
    ];
}
