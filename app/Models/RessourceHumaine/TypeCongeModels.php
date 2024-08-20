<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCongeModels extends Model
{
    use HasFactory;

    protected $fileable = ['type_conge','slug'];
}
