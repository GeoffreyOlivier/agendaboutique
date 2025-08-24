<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Produit;
use App\Models\Artisan;
use App\Services\Produit\ProduitService;
use App\Services\ProduitImageService;
use Illuminate\Support\Facades\Auth;

class ProduitController extends Controller
{
    protected ProduitService $produitService;
    protected ProduitImageService $imageService;

    public function __construct(ProduitService $produitService, ProduitImageService $imageService)
    {
        $this->produitService = $produitService;
        $this->imageService = $imageService;
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

        try {
            // Adapter les données pour le service
            $data = $request->validated();
            $data['prix_base'] = $data['prix'] ?? null;
            $data['tags'] = $data['materiaux'] ?? [];
            $data['matiere'] = $data['couleur'] ?? null;
            $data['images'] = $request->file('images');
            
            $produit = $this->produitService->createProduit($data, $artisan);
            return redirect()->route('artisan.dashboard')->with('success', 'Produit ajouté avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du produit. Veuillez réessayer.');
        }
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

        // Gestion des images avec le service
        $images = $produit->images ?? [];
        $imagesToDelete = $request->input('delete_images', []);
        
        $produit->images = $this->imageService->updateImages(
            $request->file('images', []),
            $artisan->id,
            $images,
            $imagesToDelete
        );
        $produit->save();

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès !');
    }

    public function destroy(Produit $produit)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner

        // Supprimer toutes les images du produit
        if ($produit->images) {
            $this->imageService->deleteAllImages($produit->artisan_id);
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès !');
    }
}
