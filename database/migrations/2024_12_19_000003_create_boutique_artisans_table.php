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
        Schema::create('boutique_artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('commentaire_boutique')->nullable();
            $table->text('commentaire_artisan')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->decimal('commission_pourcentage', 5, 2)->default(0);
            $table->boolean('exposition_permanente')->default(false);
            $table->boolean('exposition_temporaire')->default(false);
            $table->timestamps();
            
            $table->unique(['boutique_id', 'artisan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boutique_artisans');
    }
};
