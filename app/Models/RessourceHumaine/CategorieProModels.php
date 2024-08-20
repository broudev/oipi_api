<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProModels extends Model
{
    use HasFactory;

    protected $fileable = ['categorie_pro','slug'];
}
