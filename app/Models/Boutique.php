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
        'secteur',
        'taille',
        'specialites',
        'statut',
        'actif',
        'siret',
        'tva',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'specialites' => 'array',
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

    public function getNomCompletAttribute()
    {
        return $this->nom;
    }

    public function getSpecialitesListeAttribute()
    {
        return is_array($this->specialites) ? implode(', ', $this->specialites) : $this->specialites;
    }
}
