<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductPublicController extends Controller
{
    public function index()
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
        // Vérifier que le produit est publié et disponible
        if ($product->status !== 'published' || !$product->available) {
            abort(404);
        }

        return view('products.show-public', compact('product'));
    }
}
