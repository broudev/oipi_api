<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierFileModels extends Model
{
    use HasFactory;

    protected $fileable = ['libelle_file','dossiers_code','file_url','status_file','slug'];
}
