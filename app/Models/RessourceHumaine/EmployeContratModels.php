<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeContratModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'employe_code',
        'profession',
        'fonction',
        'service',
        'categorie_pro',
        'bureau',
        'contrats',

        'date_embauche',
        'salaire_categoriel',
        'salaire_mensuel_net',
        'slug'
    ];
}
