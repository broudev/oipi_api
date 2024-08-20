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
        Schema::create('permissions_models', function (Blueprint $table) {
            $table->id();
            $table->string('permission_ref')->nullable();
            $table->unsignedInteger('customer_id');
            $table->string('customer_name')->nullable();
            $table->string('fonction')->nullable();
            $table->string('service')->nullable();
            $table->longText('motif')->nullable();
            $table->string('permission_file')->nullable();
            $table->string('duree_permission')->nullable();
            $table->date('date_demande')->nullable();
            $table->boolean('new_actions')->default(0);
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
        Schema::dropIfExists('permissions_models');
    }
};
