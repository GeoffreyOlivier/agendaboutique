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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($artisans as $artisan)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 artisan-card">
                    <!-- Image de profil de l'artisan -->
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                        <img src="{{ asset('images/default-artisan-avatar.svg') }}" alt="Photo de profil de {{ $artisan->nom_artisan }}" class="w-32 h-32 object-contain artisan-avatar">
                    </div>
                    
                    <!-- Informations de l'artisan -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $artisan->nom_artisan }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Actif
                            </span>
                        </div>
                        
                        @if($artisan->description)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($artisan->description, 120) }}</p>
                        @endif
                        
                        <!-- Spécialités -->
                        @if($artisan->specialites && count($artisan->specialites) > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Spécialités :</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($artisan->specialites as $specialite)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full speciality-tag">
                                            {{ $specialite }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Catégories de produits -->
                        @if($artisan->produits->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Catégories :</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($artisan->produits->pluck('categorie')->unique()->filter() as $categoryName)
                                        @if($categoryName)
                                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full category-tag">
                                                {{ $categoryName }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Statistiques -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>{{ $artisan->produits->count() }} produit(s)</span>
                            @if($artisan->experience_annees)
                                <span>{{ $artisan->experience_annees }} an(s) d'expérience</span>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="#" class="flex-1 text-center btn-primary text-sm">
                                Voir les produits
                            </a>
                            <a href="#" class="btn-secondary text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </a>
                        </div>
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
