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
        Schema::table('artisans', function (Blueprint $table) {
            // Renommer les colonnes existantes
            $table->renameColumn('nom', 'nom_artisan');
            $table->renameColumn('localisation', 'adresse_atelier');
            
            // Ajouter de nouvelles colonnes
            $table->string('ville_atelier')->nullable()->after('adresse_atelier');
            $table->string('code_postal_atelier')->nullable()->after('ville_atelier');
            $table->string('telephone_atelier')->nullable()->after('code_postal_atelier');
            $table->string('email_atelier')->nullable()->after('telephone_atelier');
            $table->string('site_web')->nullable()->after('email_atelier');
            $table->json('reseaux_sociaux')->nullable()->after('site_web');
            $table->text('description')->nullable()->after('nom_artisan');
            $table->integer('experience_annees')->nullable()->after('description');
            $table->json('techniques')->nullable()->after('specialites');
            $table->json('materiaux_preferes')->nullable()->after('techniques');
            
            // Modifier les colonnes existantes pour les rendre nullable
            $table->string('telephone')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('instagram')->nullable()->change();
            $table->string('etsy')->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('website')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artisans', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropColumn([
                'ville_atelier',
                'code_postal_atelier', 
                'telephone_atelier',
                'email_atelier',
                'site_web',
                'reseaux_sociaux',
                'description',
                'experience_annees',
                'techniques',
                'materiaux_preferes'
            ]);
            
            // Renommer les colonnes
            $table->renameColumn('nom_artisan', 'nom');
            $table->renameColumn('adresse_atelier', 'localisation');
        });
    }
};
