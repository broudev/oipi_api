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
        Schema::create('employe_information_models', function (Blueprint $table) {
            $table->id();
            $table->string('employe_code', 50);
            $table->string('matricule', 50);
            $table->string('code_autorisation', 50);
            $table->string('code_owners', 100);
            $table->string('diplome', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('date_naissance');
            $table->string('lieu_naissance', 100);
            $table->string('photo')->nullable();
            $table->string('genre', 50)->nullable();
            $table->string('situation_matrimoniale')->nullable();
            $table->string('lieu_residence');
            $table->string('nationalite')->nullable();
            $table->integer('nombre_enfant_a_charge')->default(0);
            $table->string('type_piece');
            $table->string('piece_number');
            $table->string('cnps_number')->nullable();
            $table->boolean('status')->default(0);
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_information_models');
    }
};
