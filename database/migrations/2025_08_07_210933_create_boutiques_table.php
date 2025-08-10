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
        Schema::create('boutiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            
            // Adresse et contact
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays')->default('France');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            
            // Informations commerciales
            $table->string('secteur');
            $table->enum('taille', ['petite', 'moyenne', 'grande'])->default('moyenne');
            $table->text('specialites')->nullable();
            
            // Statut et validation
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->boolean('actif')->default(true);
            
            // Informations de facturation
            $table->string('siret')->nullable();
            $table->string('tva')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boutiques');
    }
};
