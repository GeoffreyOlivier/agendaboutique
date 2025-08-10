@extends('layouts.app-no-sidebar')

@section('title', 'Mes produits')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Mes produits</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez votre catalogue de produits</p>
                </div>
                <a href="{{ route('produits.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau produit
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $produits->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En boutique</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $produits->where('statut', 'disponible')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valeur totale</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($produits->sum('prix'), 2, ',', ' ') }} €</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avec photos</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $produits->filter(function($p) { return !empty($p->images); })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        @if($produits->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tous mes produits</h3>
                </div>
                
                <!-- Grille des produits -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($produits as $produit)
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Image du produit -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                @if($produit->image_principale)
                                    <img src="{{ asset('storage/' . $produit->image_principale) }}" 
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
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 truncate">{{ $produit->nom }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($produit->statut === 'publie') bg-green-100 text-green-800
                                        @elseif($produit->statut === 'brouillon') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($produit->statut) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">
                                    {{ Str::limit($produit->description, 100) }}
                                </p>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $produit->prix_formate }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($produit->categorie) }}
                                    </span>
                                </div>
                                
                                @if(!empty($produit->tags))
                                <div class="mb-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($produit->tags, 0, 3) as $materiau)
                                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                                {{ $materiau }}
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
                                        <a href="{{ route('produits.edit', $produit) }}" 
                                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Modifier
                                        </a>
                                        <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($produits->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $produits->links() }}
                </div>
                @endif
            </div>
        @else
            <!-- État vide -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Aucun produit</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Commencez par ajouter votre premier produit.</p>
                <div class="mt-6">
                    <a href="{{ route('produits.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un produit
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
