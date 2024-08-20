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
        Schema::create('imputation_courriers_responsable_service_models', function (Blueprint $table) {
            $table->id();
            $table->string('employee_matricule', 50)->nullable();
            $table->string('courrier_ref')->nullable();
            $table->date('date_imputation')->nullable();
            $table->date('date_execution')->nullable();
            $table->boolean('responsable_new_actions')->default(0);
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imputation_courriers_responsable_service_models');
    }
};
