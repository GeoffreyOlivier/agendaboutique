<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use App\Services\Shop\ShopService;
use App\Services\ShopImageService;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    protected ShopService $shopService;
    protected ShopImageService $imageService;

    public function __construct(ShopService $shopService, ShopImageService $imageService)
    {
        $this->shopService = $shopService;
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
        if (Auth::user()->shop) {
            return redirect()->route('dashboard')->with('warning', 'Vous avez déjà une boutique.');
        }

        return view('shops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        $user = Auth::user();
        
        if ($user->shop) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une boutique.');
        }
        
        try {
            $shop = $this->shopService->createShop($request->validated(), $user);
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
    public function edit(Shop $shop)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('shops.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        
        try {
            $this->shopService->updateShop($shop, $request->validated());
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
