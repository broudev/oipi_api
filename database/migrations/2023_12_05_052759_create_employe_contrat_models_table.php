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
        Schema::create('employe_contrat_models', function (Blueprint $table) {
            $table->id();
            $table->string('employe_code', 50);
            $table->string('profession', 100);
            $table->string('fonction', 100);
            $table->string('service', 100)->nullable();
            $table->string('categorie_pro')->nullable();
            $table->string('bureau', 100);
            $table->string('contrats', 100);

            $table->date('date_embauche')->nullable();
            $table->integer('salaire_categoriel')->default(0);
            $table->integer('salaire_mensuel_net')->default(0);
            $table->string('slug', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_contrat_models');
    }
};
