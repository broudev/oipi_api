<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratsModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'contrats',
        'slug'
    ];
}
