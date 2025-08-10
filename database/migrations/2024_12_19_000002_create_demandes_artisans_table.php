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
        Schema::create('demandes_artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->json('specifications')->nullable();
            $table->integer('quantite_demandee')->nullable();
            $table->decimal('budget_estime', 10, 2)->nullable();
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee', 'en_cours', 'terminee'])->default('en_attente');
            $table->text('reponse_artisan')->nullable();
            $table->decimal('prix_propose', 10, 2)->nullable();
            $table->date('date_reponse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_artisans');
    }
};
