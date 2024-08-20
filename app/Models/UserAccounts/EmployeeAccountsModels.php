<?php

namespace App\Models\UserAccounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAccountsModels extends Model
{
    use HasFactory;


    protected $fileable = [
        'user_id',
        'role_id',
        'employe_matricule',
        'type_accounts',
        'slug'
    ];
}
