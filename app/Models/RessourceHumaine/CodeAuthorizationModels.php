<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeAuthorizationModels extends Model
{
    use HasFactory;

    protected $fileable = ['code','old_code','owners','date_echeance','slug'];
}
