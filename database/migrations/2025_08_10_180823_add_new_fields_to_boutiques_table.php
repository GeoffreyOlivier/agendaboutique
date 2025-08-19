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
        Schema::table('boutiques', function (Blueprint $table) {
            // Champs pour les loyers
            $table->decimal('loyer_depot_vente', 8, 2)->nullable()->comment('Loyer pour les artisans en dépôt-vente');
            $table->decimal('loyer_permanence', 8, 2)->nullable()->comment('Loyer pour les artisans en permanence');
            
            // Champs pour les commissions
            $table->decimal('commission_depot_vente', 5, 2)->nullable()->comment('Commission % pour dépôt-vente');
            $table->decimal('commission_permanence', 5, 2)->nullable()->comment('Commission % pour permanence');
            
            // Nombre de permanences indicatif
            $table->integer('nb_permanences_mois_indicatif')->nullable()->comment('Nombre indicatif de permanences par mois (à titre indicatif)');
            
            // Réseaux sociaux
            $table->string('site_web')->nullable()->comment('Site web de la boutique');
            $table->string('instagram_url')->nullable()->comment('Lien Instagram');
            $table->string('tiktok_url')->nullable()->comment('Lien TikTok');
            $table->string('facebook_url')->nullable()->comment('Lien Facebook');
            
            // Horaires d'ouverture
            $table->text('horaires_ouverture')->nullable()->comment('Horaires d\'ouverture de la boutique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn([
                'loyer_depot_vente',
                'loyer_permanence',
                'commission_depot_vente',
                'commission_permanence',
                'nb_permanences_mois_indicatif',
                'site_web',
                'instagram_url',
                'tiktok_url',
                'facebook_url',
                'horaires_ouverture'
            ]);
        });
    }
};
