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
        Schema::create('pointages_models', function (Blueprint $table) {
            $table->id();
            $table->string('pointage_ref')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('date_arriver');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages_models');
    }
};
