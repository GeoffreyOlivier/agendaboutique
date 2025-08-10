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
        Schema::create('artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('bio')->nullable();
            $table->string('localisation');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            
            // Réseaux sociaux
            $table->string('instagram')->nullable();
            $table->string('etsy')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            
            // Statut et validation
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->boolean('actif')->default(true);
            
            // Informations commerciales
            $table->text('specialites')->nullable();
            $table->string('secteur_principal')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artisans');
    }
};
