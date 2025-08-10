<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'artisan_id',
        'nom',
        'description',
        'prix_base',
        'prix_min',
        'prix_max',
        'prix_masque',
        'categorie',
        'tags',
        'images',
        'image_principale',
        'matiere',
        'dimensions',
        'instructions_entretien',
        'statut',
        'disponible',
        'stock',
        'reference',
        'duree_fabrication',
    ];

    protected $casts = [
        'prix_base' => 'decimal:2',
        'prix_min' => 'decimal:2',
        'prix_max' => 'decimal:2',
        'prix_masque' => 'boolean',
        'tags' => 'array',
        'dimensions' => 'array',
        'images' => 'array',
        'disponible' => 'boolean',
    ];

    // Relations
    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    // Accesseurs
    public function getPrixFormateAttribute()
    {
        if ($this->prix_base) {
            return number_format($this->prix_base, 2, ',', ' ') . ' €';
        }
        if ($this->prix_min && $this->prix_max) {
            return number_format($this->prix_min, 2, ',', ' ') . ' - ' . number_format($this->prix_max, 2, ',', ' ') . ' €';
        }
        return 'Prix sur demande';
    }

    public function getImagePrincipaleAttribute()
    {
        // Si image_principale est définie dans la base de données
        if ($this->attributes['image_principale'] ?? null) {
            return $this->attributes['image_principale'];
        }
        
        // Sinon, prendre la première image du tableau images
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        
        return null;
    }

    // Alias pour compatibilité avec l'ancien code
    public function getPrixAttribute()
    {
        return $this->prix_base;
    }

    public function setPrixAttribute($value)
    {
        $this->prix_base = $value;
    }
}
