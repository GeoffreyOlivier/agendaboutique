<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitPublicController extends Controller
{
    public function index()
    {
        // Récupérer tous les produits publiés et disponibles
        $produits = Produit::where('statut', 'publie')
                          ->where('disponible', true)
                          ->with('artisan')
                          ->latest()
                          ->paginate(12);
        
        return view('produits.index-public', compact('produits'));
    }

    public function show(Produit $produit)
    {
        // Vérifier que le produit est publié et disponible
        if ($produit->statut !== 'publie' || !$produit->disponible) {
            abort(404);
        }

        return view('produits.show-public', compact('produit'));
    }
}
