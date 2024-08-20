<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Roles\RoleModels;
use App\Services\CodeGenerator;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleModels::truncate();

        RoleModels::create(['role' => 'admin', 'slug' => CodeGenerator::generateSlugCode()]);
        RoleModels::create(['role' => 'manager_rh', 'slug' => CodeGenerator::generateSlugCode()]);
        RoleModels::create(['role' => 'manager_stock', 'slug' => CodeGenerator::generateSlugCode()]);
        RoleModels::create(['role' => 'employe', 'slug' => CodeGenerator::generateSlugCode()]);
    }
}
