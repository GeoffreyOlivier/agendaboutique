<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Produit;
use App\Models\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function __construct()
    {
        // Le middleware 'role:artisan' est maintenant appliqué au niveau des routes
    }

    public function create()
    {
        $artisan = Auth::user()->artisan;
        
        if (!$artisan) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un profil artisan pour ajouter des produits.');
        }

        return view('produits.create', compact('artisan'));
    }

    public function store(StoreProduitRequest $request)
    {
        $artisan = Auth::user()->artisan;
        
        if (!$artisan) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un profil artisan pour ajouter des produits.');
        }

        $validated = $request->validated();

        $produit = new Produit();
        $produit->artisan_id = $artisan->id;
        $produit->nom = $validated['nom'];
        $produit->description = $validated['description'];
        $produit->prix_base = $validated['prix']; // Utilise prix_base au lieu de prix
        $produit->categorie = $validated['categorie'];
        $produit->tags = $validated['materiaux'] ?? []; // Utilise tags au lieu de materiaux
        $produit->dimensions = $validated['dimensions'] ?? [];
        $produit->matiere = $validated['couleur']; // Utilise matiere au lieu de couleur
        $produit->instructions_entretien = $validated['instructions_entretien'];
        $produit->statut = 'publie'; // Utilise 'publie' au lieu de 'disponible'
        $produit->disponible = true; // Utilise disponible au lieu de actif

        // Gestion des images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('produits', $filename, 'public');
                $images[] = $path;
            }
        }
        $produit->images = $images;

        $produit->save();

        return redirect()->route('artisan.dashboard')->with('success', 'Produit ajouté avec succès !');
    }

    public function index()
    {
        $artisan = Auth::user()->artisan;
        $produits = $artisan ? $artisan->produits()->latest()->paginate(10) : collect();
        
        return view('produits.index', compact('produits', 'artisan'));
    }

    public function indexPublic()
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
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('produits.edit', compact('produit'));
    }

    public function update(UpdateProduitRequest $request, Produit $produit)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner

        $validated = $request->validated();

        $produit->nom = $validated['nom'];
        $produit->description = $validated['description'];
        $produit->prix_base = $validated['prix'];
        $produit->categorie = $validated['categorie'];
        $produit->tags = $validated['materiaux'] ?? [];
        $produit->dimensions = $validated['dimensions'] ?? [];
        $produit->matiere = $validated['couleur'];
        $produit->instructions_entretien = $validated['instructions_entretien'];

        // Gestion des images existantes et suppression
        $images = $produit->images ?? [];
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $imageToDelete) {
                // Trouver l'index de l'image à supprimer
                $index = array_search($imageToDelete, $images);
                if ($index !== false) {
                    // Supprimer l'image du stockage
                    Storage::disk('public')->delete($imageToDelete);
                    // Retirer l'image du tableau
                    unset($images[$index]);
                }
            }
            // Réindexer le tableau
            $images = array_values($images);
        }

        // Ajouter de nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('produits', $filename, 'public');
                $images[] = $path;
            }
        }

        $produit->images = $images;
        $produit->save();

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès !');
    }

    public function destroy(Produit $produit)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner

        // Supprimer les images du stockage
        if ($produit->images) {
            foreach ($produit->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès !');
    }
}
