<?php

namespace App\Models\CourrierManagments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendCourrierModels extends Model
{
    use HasFactory;


    protected $fileable = [
        'courrier_send_ref',
        'telephone_destinataire',
        'type_send',
        'objet_courrier',
        'document_courrier_url',
        'adress_email_destinataire',
        'designation_destinataire',
        'destinataire',
        'retour_courrier',
        'date_reception',
        'slug',
    ];
}
