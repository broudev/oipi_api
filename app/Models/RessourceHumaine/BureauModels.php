<?php

namespace App\Models\RessourceHumaine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BureauModels extends Model
{
    use HasFactory;

    protected $fileable = ['bureau','slug'];
}
