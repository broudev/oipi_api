<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointageConfigModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'initial_hour',
        'tolerable_hour',
        'out_hour',
        'slug'
    ];
}
