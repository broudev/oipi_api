<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dossier_employes_models', function (Blueprint $table) {
            $table->id();
            $table->string('employe_matricule')->nullable();
            $table->string('dossiers_code')->nullable();
            $table->string('libelle_dossiers')->nullable();
            $table->boolean('status_folder')->default(0);
            $table->string('slug', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_employes_models');
    }
};
