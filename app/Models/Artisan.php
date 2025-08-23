<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom_artisan',
        'description',
        'specialites',
        'experience_annees',
        'adresse_atelier',
        'ville_atelier',
        'code_postal_atelier',
        'telephone_atelier',
        'email_atelier',
        'site_web',
        'reseaux_sociaux',
        'statut',
        'actif',
        'bio',
        'techniques',
        'materiaux_preferes',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'reseaux_sociaux' => 'array',
        'specialites' => 'array',
        'techniques' => 'array',
        'materiaux_preferes' => 'array',
    ];


    public function getSpecialitesAttribute($value)
    {
        if (is_array($value)) return $value;

        if (is_string($value)) {
            $first = json_decode($value, true);
            if (is_array($first)) return $first;

            // si le 1er decode retourne une string qui ressemble à un tableau JSON
            if (is_string($first) && str_starts_with($first, '[')) {
                $second = json_decode($first, true);
                if (is_array($second)) return $second;
            }
        }

        return []; // valeur sûre
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeArtisan::class);
    }

    public function boutiques(): HasMany
    {
        return $this->hasMany(BoutiqueArtisan::class);
    }

    // Scopes
    public function scopeApprouves($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    // Accesseurs
    public function getAdresseCompleteAttribute()
    {
        return "{$this->adresse_atelier}, {$this->code_postal_atelier} {$this->ville_atelier}";
    }

    public function getNomCompletAttribute()
    {
        return $this->nom_artisan;
    }

    public function getSpecialitesListeAttribute()
    {
        return is_array($this->specialites) ? implode(', ', $this->specialites) : $this->specialites;
    }
}
