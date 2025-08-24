<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoutiqueRequest;
use App\Http\Requests\UpdateBoutiqueRequest;
use App\Models\Boutique;
use App\Services\Boutique\BoutiqueService;
use App\Services\Boutique\BoutiqueImageService;
use Illuminate\Support\Facades\Auth;

class BoutiqueController extends Controller
{
    protected BoutiqueService $boutiqueService;
    protected BoutiqueImageService $imageService;

    public function __construct(BoutiqueService $boutiqueService, BoutiqueImageService $imageService)
    {
        $this->boutiqueService = $boutiqueService;
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
        
        try {
            $boutique = $this->boutiqueService->createBoutique($request->validated(), $user);
            return redirect()->route('dashboard')->with('success', 'Votre boutique a été créée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la boutique. Veuillez réessayer.');
        }
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
        
        try {
            $this->boutiqueService->updateBoutique($boutique, $request->validated());
            return redirect()->route('dashboard')->with('success', 'Votre boutique a été mise à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la boutique. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
