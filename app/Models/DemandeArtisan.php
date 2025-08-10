<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeArtisan extends Model
{
    use HasFactory;

    protected $table = 'demandes_artisans';

    protected $fillable = [
        'boutique_id',
        'artisan_id',
        'titre',
        'description',
        'specifications',
        'quantite_demandee',
        'budget_estime',
        'date_limite',
        'statut',
        'reponse_artisan',
        'prix_propose',
        'date_reponse',
    ];

    protected $casts = [
        'specifications' => 'array',
        'budget_estime' => 'decimal:2',
        'prix_propose' => 'decimal:2',
        'date_limite' => 'date',
        'date_reponse' => 'date',
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
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }

    // Accesseurs
    public function getBudgetFormateAttribute()
    {
        return $this->budget_estime ? number_format($this->budget_estime, 2, ',', ' ') . ' €' : 'Non spécifié';
    }

    public function getPrixProposeFormateAttribute()
    {
        return $this->prix_propose ? number_format($this->prix_propose, 2, ',', ' ') . ' €' : 'Non spécifié';
    }

    public function getEstUrgenteAttribute()
    {
        if (!$this->date_limite) return false;
        return $this->date_limite->diffInDays(now()) <= 7;
    }
}
