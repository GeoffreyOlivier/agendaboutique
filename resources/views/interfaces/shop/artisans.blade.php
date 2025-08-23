@extends('layouts.app')

@section('styles')
@vite(['resources/themes/anchor/assets/css/app.css'])
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête de la page -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Tous les Artisans
                </h1>
                <p class="text-gray-600 mt-2">
                    @if($boutique)
                        Découvrez les talents et créateurs qui font vivre votre boutique
                    @else
                        Découvrez tous les artisans disponibles pour votre future boutique
                    @endif
                </p>
            </div>
            <a href="{{ route('shop.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au tableau de bord
            </a>
        </div>
    </div>

    <!-- Statistiques des artisans -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 stats-grid">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Artisans</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $artisans->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Produits Disponibles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $artisans->sum(function($artisan) { return $artisan->produits->count(); }) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Catégories</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $artisans->flatMap(function($artisan) { return $artisan->produits->pluck('categorie'); })->unique()->filter()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher un artisan</label>
                <input type="text" id="search" placeholder="Nom, spécialité, catégorie..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:w-48">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($artisans->flatMap(function($artisan) { return $artisan->produits->pluck('categorie'); })->unique()->filter() as $categoryName)
                        @if($categoryName)
                            <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des artisans -->
    @if($artisans->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($artisans as $artisan)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 artisan-card">
                    <!-- Première ligne : Informations de l'artisan -->
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <!-- Photo de profil -->
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center">
                                    <img src="{{ asset('images/default-artisan-avatar.svg') }}" alt="Photo de profil de {{ $artisan->nom_artisan }}" class="w-16 h-16 object-contain rounded-full">
                                </div>
                            </div>
                            
                            <!-- Informations principales -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $artisan->nom_artisan }}</h3>
                                        <button class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="Ajouter aux favoris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                </div>
                                
                                @if($artisan->description)
                                    <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($artisan->description, 120) }}</p>
                                @endif
                                
                                <!-- Spécialités -->
                                @if($artisan->specialite)
                                    <div class="mb-3">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($artisan->specialites as $specialite)
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                    {{ $specialite }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Statistiques -->
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $artisan->produits->count() }} produit(s)</span>
                                    @if($artisan->experience_annees)
                                        <span>{{ $artisan->experience_annees }} an(s) d'expérience</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bouton voir le profil -->
                        <div class="text-center">
                            <a href="{{ route('shop.artisan.profile', $artisan->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Voir le profil
                            </a>
                        </div>
                    </div>
                    
                    <!-- Deuxième ligne : Photos des produits -->
                    <div class="bg-gray-50 p-4">
                        @if($artisan->produits->count() > 0)
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($artisan->produits->take(3) as $produit)
                                    <div class="bg-white rounded-lg p-2 shadow-sm">
                                        <div class="aspect-square bg-gradient-to-br from-gray-200 to-gray-300 rounded-md flex items-center justify-center mb-2">
                                            @if($produit->image_principale)
                                                <img src="{{ asset('storage/' . $produit->image_principale) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover rounded-md">
                                            @else
                                                <img src="{{ asset('images/products/default-product.svg') }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover rounded-md">
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs font-medium text-gray-900 truncate">{{ Str::limit($produit->nom, 15) }}</p>
                                            <p class="text-xs text-gray-500">{{ $produit->prix_formate }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <!-- Si moins de 3 produits, afficher des placeholders -->
                                @for($i = $artisan->produits->count(); $i < 3; $i++)
                                    <div class="bg-white rounded-lg p-2 shadow-sm">
                                        <div class="aspect-square bg-gray-100 rounded-md flex items-center justify-center">
                                            <div class="text-gray-300 text-xs text-center">
                                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Vide
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Aucun produit</p>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-300 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500">Aucun produit disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Message si aucun artisan -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun artisan associé</h3>
            <p class="text-gray-500 mb-6">
                Vous n'avez pas encore d'artisans associés à votre boutique. Commencez par approuver des demandes d'artisans.
            </p>
            <a href="{{ route('shop.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au tableau de bord
            </a>
        </div>
    @endif
</div>

<script>
// Fonctionnalité de recherche et filtrage
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const artisanCards = document.querySelectorAll('.grid > div');
    
    function filterArtisans() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categorySelect.value.toLowerCase();
        
        artisanCards.forEach(card => {
            const artisanName = card.querySelector('h3').textContent.toLowerCase();
            const artisanDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
            const specialites = Array.from(card.querySelectorAll('.bg-blue-100')).map(el => el.textContent.toLowerCase());
            const categories = Array.from(card.querySelectorAll('.bg-purple-100')).map(el => el.textContent.toLowerCase());
            
            const matchesSearch = artisanName.includes(searchTerm) || 
                                artisanDescription.includes(searchTerm) || 
                                specialites.some(s => s.includes(searchTerm));
            
            const matchesCategory = !selectedCategory || categories.some(c => c.includes(selectedCategory));
            
            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterArtisans);
    categorySelect.addEventListener('change', filterArtisans);
});
</script>
@endsection
