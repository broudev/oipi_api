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
        Schema::create('send_courrier_models', function (Blueprint $table) {
            $table->id();
            $table->string('courrier_send_ref')->nullable();
            $table->string('telephone_destinataire')->nullable();
            $table->string('type_send')->nullable();
            $table->string('adress_email_destinataire')->nullable();
            $table->string('designation_destinataire')->nullable();
            $table->string('destinataire')->nullable();
            $table->string('document_courrier_url')->nullable();
            $table->longText('retour_courrier')->nullable();
            $table->longText('objet_courrier')->nullable();
            $table->date('date_reception')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_courrier_models');
    }
};
