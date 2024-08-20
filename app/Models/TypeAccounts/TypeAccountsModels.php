<?php

namespace App\Models\TypeAccounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAccountsModels extends Model
{
    use HasFactory;

    protected $fileable = [
        'type_accounts',
        'slug'
    ];
}
