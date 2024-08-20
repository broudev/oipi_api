<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModels extends Model
{
    use HasFactory;

    protected $fileable = ['service','slug'];
}
