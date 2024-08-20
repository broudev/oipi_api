<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CongesModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'conge_ref',
        'customer_id',
        'customer_name',
        'fonction',
        'service',
        'type_conge',
        'objet_demande',
        'motif',
        'date_demande',
        'destinataire',
        'duree_conge',
        'date_depart',
        'date_retour',
        'new_actions',
        'status_on',
        'status_off',
        'slug',
    ];
}
