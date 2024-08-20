<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointagesModels extends Model
{
    use HasFactory;


    protected $fileable = [
        'pointage_ref',
        'customer_name',
        'date_arriver',
        'heure_debut',
        'heure_fin',
        'slug',
    ];
}
