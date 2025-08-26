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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('description');
            
            // Prix (masqué pour les vitrines publiques)
            $table->decimal('prix_base', 10, 2)->nullable();
            $table->decimal('prix_min', 10, 2)->nullable();
            $table->decimal('prix_max', 10, 2)->nullable();
            $table->boolean('prix_masque')->default(true);
            
            // Catégories et tags
            $table->string('categorie')->nullable();
            $table->json('tags')->nullable();
            
            // Images
            $table->json('images')->nullable(); // URLs des images
            $table->string('image_principale')->nullable();
            
            // Informations techniques
            $table->string('matiere')->nullable();
            $table->string('dimensions')->nullable();
            $table->text('instructions_entretien')->nullable();
            
            // Statut et disponibilité
            $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->boolean('disponible')->default(true);
            $table->integer('stock')->nullable();
            
            // Métadonnées
            $table->string('reference')->nullable();
            $table->integer('duree_fabrication')->nullable(); // en jours
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
