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
        Schema::create('conges_models', function (Blueprint $table) {
            $table->id();
            $table->string('conge_ref')->nullable();
            $table->unsignedInteger('customer_id');
            $table->string('customer_name')->nullable();
            $table->string('fonction')->nullable();
            $table->string('service')->nullable();
            $table->string('type_conge')->nullable();
            $table->text('objet_demande')->nullable();
            $table->longText('motif')->nullable();
            $table->string('destinataire')->nullable();
            $table->string('duree_conge')->nullable();
            $table->date('date_depart')->nullable();
            $table->date('date_retour')->nullable();
            $table->boolean('new_actions')->default(false);
            $table->boolean('status_on')->default(false);
            $table->boolean('status_off')->default(false);
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conges_models');
    }
};
