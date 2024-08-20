<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeInformationModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'employe_code',
        'matricule',
        'first_name',
        'last_name',
        'adresse_email',
        'date_naissance',
        'lieu_naissance',
        'photo',
        'genre',
        'situation_matrimoniale',
        'lieu_residence',

        'nationalite',
        'nombre_enfant_a_charge',
        'type_piece',
        'piece_number',
        'cnps_number',
        'is_responsible',
        'is_agents',
        'status',
        'slug'
    ];


}
