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
        Schema::create('code_authorization_models', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('old_code')->nullable();
            $table->string('owners')->nullable();
            $table->date('date_echeance')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_authorization_models');
    }
};
