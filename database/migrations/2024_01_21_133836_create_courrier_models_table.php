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
        Schema::create('courrier_models', function (Blueprint $table) {
            $table->id();
            $table->string('courrier_ref')->nullable();
            $table->string('provenance')->nullable();
            $table->string('document_courrier_url')->nullable();
            $table->date('date_started')->nullable();
            $table->longText('objet_courrier')->nullable();
            $table->longText('retour_courrier')->nullable();
            $table->boolean('new_actions')->default(0);
            $table->boolean('status_responsable_imputed')->default(0);
            $table->boolean('status_agent_imputed')->default(0);
            $table->boolean('status_completed')->default(0);
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courrier_models');
    }
};
