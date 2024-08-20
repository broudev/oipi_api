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
        Schema::create('employee_accounts_models', function (Blueprint $table) {
            $table->id();
            $table->string('employe_matricule')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('type_accounts')->nullable();
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
        Schema::dropIfExists('employee_accounts_models');
    }
};
