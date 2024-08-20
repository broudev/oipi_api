<?php

namespace Database\Seeders;

use App\Models\RessourceHumaine\CodeAuthorizationModels;
use App\Services\CodeGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CodeAuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date('Y-m-d', strtotime(now()));


        CodeAuthorizationModels::truncate();

        CodeAuthorizationModels::create([
            'owners' => 'administration',
            'date_echeance' => $date,
            'code' => CodeGenerator::generateCodeAuthorization(),
            'slug' => CodeGenerator::generateSlugCode()
        ]);
        CodeAuthorizationModels::create([
            'owners' => 'ressource_humaine',
            'date_echeance' => $date,
            'code' => CodeGenerator::generateCodeAuthorization(),
            'slug' => CodeGenerator::generateSlugCode()
        ]);
        CodeAuthorizationModels::create([
            'owners' => 'gestion_stock',
            'date_echeance' => $date,
            'code' => CodeGenerator::generateCodeAuthorization(),
            'slug' => CodeGenerator::generateSlugCode()
        ]);
        CodeAuthorizationModels::create([
            'owners' => 'gestion_courier',
            'date_echeance' => $date,
            'code' => CodeGenerator::generateCodeAuthorization(),
            'slug' => CodeGenerator::generateSlugCode()
        ]);
    }
}
