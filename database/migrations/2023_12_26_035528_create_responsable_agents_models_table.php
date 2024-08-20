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
        Schema::create('responsable_agents_models', function (Blueprint $table) {
            $table->id();
            $table->string('employee_matricule')->nullable();
            $table->unsignedInteger('employee_service_id');
            $table->string('type_responsable_agent')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsable_agents_models');
    }
};
