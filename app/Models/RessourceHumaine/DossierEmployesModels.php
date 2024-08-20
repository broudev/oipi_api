<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierEmployesModels extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_matricule',
        'dossiers_code',
        'libelle_dossiers',
        'status_folder_on',
        'status_folder_off',
        'slug',
    ];
}
