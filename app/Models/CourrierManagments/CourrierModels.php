<?php

namespace App\Models\CourrierManagments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'courrier_ref',
        'provenance',
        'date_started',
        'objet_courrier',
        'document_courrier_url',
        'new_actions',
        'status_responsable_imputed',
        'status_agent_imputed',
        'status_completed',
        'slug',
    ];
}
