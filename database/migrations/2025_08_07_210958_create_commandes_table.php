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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->string('numero_commande')->unique();
            
            // products commandés
            $table->json('products'); // [{produit_id, quantite, prix_adapte}]
            
            // Prix et facturation
            $table->decimal('total_ht', 10, 2);
            $table->decimal('total_ttc', 10, 2);
            $table->decimal('tva', 5, 2)->default(20.00);
            $table->decimal('remise', 5, 2)->default(0.00);
            
            // Statut de la commande
            $table->enum('statut', [
                'en_attente', 
                'confirmee', 
                'en_preparation', 
                'expediee', 
                'livree', 
                'annulee'
            ])->default('en_attente');
            
            // Informations de livraison
            $table->string('adresse_livraison')->nullable();
            $table->string('ville_livraison')->nullable();
            $table->string('code_postal_livraison')->nullable();
            $table->text('notes_livraison')->nullable();
            
            // Dates importantes
            $table->date('date_livraison_souhaitee')->nullable();
            $table->date('date_livraison_effective')->nullable();
            
            // Informations de paiement
            $table->string('mode_paiement')->nullable();
            $table->string('reference_paiement')->nullable();
            $table->boolean('paye')->default(false);
            
            // Notes et commentaires
            $table->text('notes_boutique')->nullable();
            $table->text('notes_artisan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
