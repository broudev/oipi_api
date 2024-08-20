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
        Schema::create('pointage_config_models', function (Blueprint $table) {
            $table->id();
            $table->time('initial_hour')->nullable();
            $table->time('tolerable_hour')->nullable();
            $table->time('out_hour')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointage_config_models');
    }
};
