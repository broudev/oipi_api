<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FonctionModels extends Model
{
    use HasFactory;

    protected $fileable = ['fonction','slug'];
}
