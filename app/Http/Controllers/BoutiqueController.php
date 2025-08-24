<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoutiqueRequest;
use App\Http\Requests\UpdateBoutiqueRequest;
use App\Models\Boutique;
use App\Services\BoutiqueImageService;
use Illuminate\Support\Facades\Auth;

class BoutiqueController extends Controller
{
    protected BoutiqueImageService $imageService;

    public function __construct(BoutiqueImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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
        // Vérifier que l'utilisateur n'a pas déjà une boutique
        if (Auth::user()->boutique) {
            return redirect()->route('dashboard')->with('warning', 'Vous avez déjà une boutique.');
        }

        return view('boutiques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoutiqueRequest $request)
    {
        $user = Auth::user();
        
        if ($user->boutique) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une boutique.');
        }
        
        $validated = $request->validated();
        
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
        
        return redirect()->route('dashboard')->with('success', 'Votre boutique a été créée avec succès !');
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
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('boutiques.edit', compact('boutique'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoutiqueRequest $request, Boutique $boutique)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        
        $validated = $request->validated();
        
        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $photoResult = $this->imageService->updatePhoto(
                $request->file('photo'),
                $boutique->id,
                $boutique->photo
            );
            $validated['photo'] = $photoResult['path'];
        }
        
        $boutique->update($validated);
        
        return redirect()->route('dashboard')->with('success', 'Votre boutique a été mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
