@extends('layouts.app-no-sidebar')

@section('title', 'Nos produits')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">Nos produits artisanaux</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">Découvrez notre sélection de créations uniques</p>
        </div>

        <!-- Filtres et recherche -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie</label>
                        <select id="categorie" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Toutes les catégories</option>
                            <option value="mobilier">Mobilier</option>
                            <option value="decoration">Décoration</option>
                            <option value="bijoux">Bijoux</option>
                            <option value="vetements">Vêtements</option>
                            <option value="accessoires">Accessoires</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix maximum</label>
                        <input type="range" id="prix" min="0" max="1000" step="10" class="w-full">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>0€</span>
                            <span id="prix-value">500€</span>
                            <span>1000€</span>
                        </div>
                    </div>
                    
                    <div>
                        <label for="recherche" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recherche</label>
                        <input type="text" id="recherche" placeholder="Nom du produit..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        @if($produits->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($produits as $produit)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Image du produit -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                        @if($produit->images && count($produit->images) > 0)
                            <img src="{{ asset('storage/' . $produit->images[0]) }}" 
                                 alt="{{ $produit->nom }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Informations du produit -->
                    <div class="p-4">
                        <div class="mb-2">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 truncate">{{ $produit->nom }}</h4>
                            @if($produit->artisan)
                                <p class="text-sm text-gray-500 dark:text-gray-400">par {{ $produit->artisan->nom }}</p>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ Str::limit($produit->description, 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($produit->prix_base, 2, ',', ' ') }} €
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ ucfirst($produit->categorie) }}
                            </span>
                        </div>
                        
                        @if($produit->tags && count($produit->tags) > 0)
                        <div class="mb-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($produit->tags, 0, 3) as $tag)
                                    <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                                @if(count($produit->tags) > 3)
                                    <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                        +{{ count($produit->tags) - 3 }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $produit->created_at->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('shop.artisan.profile', $produit->artisan->id) }}" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors inline-block">
                                    Contacter
                                </a>
                                <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Détails
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($produits->hasPages())
            <div class="mt-8">
                {{ $produits->links() }}
            </div>
            @endif
        @else
            <!-- État vide -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Aucun produit trouvé</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Aucun produit ne correspond à vos critères de recherche.</p>
            </div>
        @endif
    </div>
</div>

<script>
// Mise à jour de l'affichage du prix
document.getElementById('prix').addEventListener('input', function() {
    document.getElementById('prix-value').textContent = this.value + '€';
});
</script>
@endsection
