<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boutique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BoutiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier que l'utilisateur a le rôle shop
        if (!Auth::user()->hasRole('shop')) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'utilisateur n'a pas déjà une boutique
        if (Auth::user()->boutique) {
            return redirect()->route('shop.dashboard')->with('warning', 'Vous avez déjà une boutique.');
        }

        return view('boutiques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        if ($user->boutique) {
            return redirect()->route('shop.dashboard')->with('error', 'Vous avez déjà une boutique.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'taille' => 'required|in:petite,moyenne,grande',
            'siret' => 'nullable|string|max:14',
            'tva' => 'nullable|string|max:20',
            'loyer_depot_vente' => 'nullable|numeric|min:0',
            'loyer_permanence' => 'nullable|numeric|min:0',
            'commission_depot_vente' => 'nullable|numeric|min:0|max:100',
            'commission_permanence' => 'nullable|numeric|min:0|max:100',
            'nb_permanences_mois_indicatif' => 'nullable|integer|min:0',
            'site_web' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'horaires_ouverture' => 'nullable|string',
        ]);
        
        $boutique = Boutique::create([
            'user_id' => $user->id,
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'adresse' => $validated['adresse'],
            'ville' => $validated['ville'],
            'code_postal' => $validated['code_postal'],
            'pays' => $validated['pays'],
            'telephone' => $validated['telephone'],
            'email' => $validated['email'],
            'taille' => $validated['taille'],
            'siret' => $validated['siret'],
            'tva' => $validated['tva'],
            'loyer_depot_vente' => $validated['loyer_depot_vente'],
            'loyer_permanence' => $validated['loyer_permanence'],
            'commission_depot_vente' => $validated['commission_depot_vente'],
            'commission_permanence' => $validated['commission_permanence'],
            'nb_permanences_mois_indicatif' => $validated['nb_permanences_mois_indicatif'],
            'site_web' => $validated['site_web'],
            'instagram_url' => $validated['instagram_url'],
            'tiktok_url' => $validated['tiktok_url'],
            'facebook_url' => $validated['facebook_url'],
            'horaires_ouverture' => $validated['horaires_ouverture'],
            'statut' => 'en_attente',
            'actif' => true,
        ]);
        
        return redirect()->route('shop.dashboard')->with('success', 'Votre boutique a été créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Boutique $boutique)
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        if ($boutique->user_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous ne pouvez pas modifier cette boutique.');
        }
        
        return view('boutiques.edit', compact('boutique'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Boutique $boutique)
    {
        $user = Auth::user();
        
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        if ($boutique->user_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous ne pouvez pas modifier cette boutique.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'taille' => 'required|in:petite,moyenne,grande',
            'siret' => 'nullable|string|max:14',
            'tva' => 'nullable|string|max:20',
            'loyer_depot_vente' => 'nullable|numeric|min:0',
            'loyer_permanence' => 'nullable|numeric|min:0',
            'commission_depot_vente' => 'nullable|numeric|min:0|max:100',
            'commission_permanence' => 'nullable|numeric|min:0|max:100',
            'nb_permanences_mois_indicatif' => 'nullable|integer|min:0',
            'site_web' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'horaires_ouverture' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($boutique->photo && Storage::disk('public')->exists($boutique->photo)) {
                Storage::disk('public')->delete($boutique->photo);
            }
            
            // Stocker la nouvelle photo
            $photoPath = $request->file('photo')->store('boutiques/photos', 'public');
            $validated['photo'] = $photoPath;
        }
        
        $boutique->update($validated);
        
        return redirect()->route('shop.dashboard')->with('success', 'Votre boutique a été mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
