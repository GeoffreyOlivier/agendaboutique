<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoutiqueArtisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'artisan_id',
        'statut',
        'commentaire_boutique',
        'commentaire_artisan',
        'date_debut',
        'date_fin',
        'commission_pourcentage',
        'exposition_permanente',
        'exposition_temporaire',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'commission_pourcentage' => 'decimal:2',
        'exposition_permanente' => 'boolean',
        'exposition_temporaire' => 'boolean',
    ];

    // Relations
    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    // Scopes
    public function scopeApprouvees($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeExpositionsPermanentes($query)
    {
        return $query->where('exposition_permanente', true);
    }

    public function scopeExpositionsTemporaires($query)
    {
        return $query->where('exposition_temporaire', true);
    }

    // Accesseurs
    public function getCommissionFormateAttribute()
    {
        return $this->commission_pourcentage . '%';
    }

    public function getEstActiveAttribute()
    {
        if (!$this->date_debut) return true;
        if (!$this->date_fin) return $this->date_debut <= now();
        return $this->date_debut <= now() && $this->date_fin >= now();
    }
}
