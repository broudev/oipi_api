<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultEmployeInfoModels extends Model
{
    use HasFactory;

    protected $fileable = [
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
        'piece_number',
        'cnps_number',
        'status',
        'slug'
    ];
}
