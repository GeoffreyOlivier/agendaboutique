<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boutique extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'description',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'telephone',
        'email',
        'taille',
        'siret',
        'tva',
        'loyer_depot_vente',
        'loyer_permanence',
        'commission_depot_vente',
        'commission_permanence',
        'nb_permanences_mois_indicatif',
        'site_web',
        'instagram_url',
        'tiktok_url',
        'facebook_url',
        'horaires_ouverture',
        'photo',
        'statut',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeArtisan::class);
    }

    public function artisans(): BelongsToMany
    {
        return $this->belongsToMany(Artisan::class, 'boutique_artisans')
                    ->withPivot(['statut', 'commentaire_boutique', 'commentaire_artisan', 'date_debut', 'date_fin', 'commission_pourcentage', 'exposition_permanente', 'exposition_temporaire'])
                    ->withTimestamps();
    }

    public function boutiqueArtisans(): HasMany
    {
        return $this->hasMany(BoutiqueArtisan::class);
    }

    // Scopes
    public function scopeApprouvees($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    // Accesseurs
    public function getAdresseCompleteAttribute()
    {
        return "{$this->adresse}, {$this->code_postal} {$this->ville}";
    }

    public function getNombreExposantsAttribute()
    {
        return match($this->taille) {
            'petite' => '1-5 exposants',
            'moyenne' => '6-15 exposants',
            'grande' => '16+ exposants',
            default => 'Non défini'
        };
    }

    public function getNomCompletAttribute()
    {
        return $this->nom;
    }


}
