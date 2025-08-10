<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'artisan_id',
        'numero_commande',
        'produits',
        'total_ht',
        'total_ttc',
        'tva',
        'remise',
        'statut',
        'adresse_livraison',
        'ville_livraison',
        'code_postal_livraison',
        'notes_livraison',
        'date_livraison_souhaitee',
        'date_livraison_effective',
        'mode_paiement',
        'reference_paiement',
        'paye',
        'notes_boutique',
        'notes_artisan',
    ];

    protected $casts = [
        'produits' => 'array',
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'tva' => 'decimal:2',
        'remise' => 'decimal:2',
        'paye' => 'boolean',
        'date_livraison_souhaitee' => 'date',
        'date_livraison_effective' => 'date',
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
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopePayees($query)
    {
        return $query->where('paye', true);
    }

    public function scopeNonPayees($query)
    {
        return $query->where('paye', false);
    }

    // Accesseurs
    public function getStatutLibelleAttribute()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getTotalFormateAttribute()
    {
        return number_format($this->total_ttc, 2, ',', ' ') . '€';
    }

    public function getProduitsDetailsAttribute()
    {
        if (!$this->produits) return [];
        
        $produits = [];
        foreach ($this->produits as $produit) {
            $produitModel = Produit::find($produit['produit_id']);
            if ($produitModel) {
                $produits[] = [
                    'produit' => $produitModel,
                    'quantite' => $produit['quantite'],
                    'prix_adapte' => $produit['prix_adapte'],
                ];
            }
        }
        
        return $produits;
    }
}
