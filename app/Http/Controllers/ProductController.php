<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Craftsman;
use App\Services\Product\ProductService;
use App\Services\Product\ProductImageService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $productService;
    protected ProductImageService $imageService;

    public function __construct(ProductService $productService, ProductImageService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
        // Le middleware 'role:craftsman' est maintenant appliqué au niveau des routes
    }

    public function create()
    {
        $craftsman = Auth::user()->craftsman;
        
        if (!$craftsman) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un profil craftsman pour ajouter des products.');
        }

        return view('products.create', compact('craftsman'));
    }

    public function store(StoreProductRequest $request)
    {
        $craftsman = Auth::user()->craftsman;
        
        if (!$craftsman) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un profil craftsman pour ajouter des products.');
        }

        try {
            // Log des données reçues pour debug
            \Log::info('ProductController store - Data received:', $request->all());
            
            // Adapter les données pour le service
            $data = $request->validated();
            $data['images'] = $request->file('images');
            
            \Log::info('ProductController store - Data validated:', $data);
            
            $product = $this->productService->createProduct($data, $craftsman);
            
            \Log::info('ProductController store - Product created:', ['id' => $product->id]);
            
            return redirect()->route('craftsman.dashboard')->with('success', 'Produit ajouté avec succès !');
        } catch (\Exception $e) {
            \Log::error('ProductController store error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Erreur lors de la création du produit : ' . $e->getMessage());
        }
    }

    public function index()
    {
        $craftsman = Auth::user()->craftsman;
        $products = $craftsman ? $craftsman->products()->latest()->paginate(10) : collect();
        
        return view('products.index', compact('products', 'craftsman'));
    }

    public function indexPublic()
    {
        // Récupérer tous les products publiés et disponibles
        $products = Product::where('status', 'published')
                          ->where('available', true)
                          ->with('craftsman')
                          ->latest()
                          ->paginate(12);
        
        return view('products.index-public', compact('products'));
    }

    public function show(Product $product)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner

        $validated = $request->validated();

        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->base_price = $validated['price'];
        $product->category = $validated['category'];
        $product->tags = $validated['materials'] ?? [];
        $product->dimensions = $validated['dimensions'] ?? [];
        $product->material = $validated['color'];
        $product->care_instructions = $validated['care_instructions'];

        // Gestion des images avec le service
        $images = $product->images ?? [];
        $imagesToDelete = $request->input('delete_images', []);
        
        $product->images = $this->imageService->updateImages(
            $request->file('images', []),
            $craftsman->id,
            $images,
            $imagesToDelete
        );
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    public function destroy(Product $product)
    {
        // La vérification de propriété est maintenant gérée par le middleware resource.owner

        // Supprimer toutes les images du produit
        if ($product->images) {
            $this->imageService->deleteAllImages($product->craftsman_id);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès !');
    }
}
