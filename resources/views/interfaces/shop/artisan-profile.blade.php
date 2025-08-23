@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête de la page -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Profil de {{ $artisan->nom_artisan }}
                </h1>
                <p class="text-gray-600 mt-2">
                    Découvrez le talent et les créations de cet artisan
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('shop.artisans') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour aux artisans
                </a>
                <a href="{{ route('shop.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Tableau de bord
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche : Informations de l'artisan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <!-- Photo de profil -->
                <div class="text-center mb-6">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center mb-4">
                        <img src="{{ asset('images/default-artisan-avatar.svg') }}" alt="Photo de profil de {{ $artisan->nom_artisan }}" class="w-28 h-28 object-contain rounded-full">
                    </div>
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $artisan->nom_artisan }}</h2>
                        <button class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Ajouter aux favoris">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                        Actif
                    </span>
                </div>

                <!-- Description -->
                @if($artisan->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">À propos</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $artisan->description }}</p>
                    </div>
                @endif


                @if(!empty($artisan->specialites))
                    @foreach($artisan->specialites as $specialite)
                        <span class="px-3 py-1 text-sm gap-2 bg-blue-100 text-blue-800 rounded-full">
                        {{ $specialite }}
                        </span>
                    @endforeach
                @endif


                <!-- Techniques -->
                @if($artisan->techniques && count($artisan->techniques) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Techniques</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($artisan->techniques as $technique)
                                <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                                    {{ $technique }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Matériaux préférés -->
                @if($artisan->materiaux_preferes && count($artisan->materiaux_preferes) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Matériaux préférés</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($artisan->materiaux_preferes as $materiau)
                                <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $materiau }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Statistiques -->
                <div class="border-t pt-6">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $artisan->produits->count() }}</p>
                            <p class="text-sm text-gray-500">Produits</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $artisan->experience_annees ?? 0 }}</p>
                            <p class="text-sm text-gray-500">Années d'expérience</p>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                @if($artisan->email_atelier || $artisan->telephone_atelier)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact</h3>
                        <div class="space-y-2">
                            @if($artisan->email_atelier)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:{{ $artisan->email_atelier }}" class="hover:text-blue-600">{{ $artisan->email_atelier }}</a>
                                </div>
                            @endif
                            @if($artisan->telephone_atelier)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:{{ $artisan->telephone_atelier }}" class="hover:text-blue-600">{{ $artisan->telephone_atelier }}</a>
                                </div>
                            @endif
                            @if($artisan->adresse_atelier)
                                <div class="flex items-start text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $artisan->adresse_complete }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Bouton Contacter l'artisan -->
                        <div class="mt-4">
                            <button class="w-full bg-green-600 text-white px-4 py-3 rounded-md text-sm font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Contacter l'artisan
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite : Produits de l'artisan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        Produits de {{ $artisan->nom_artisan }}
                    </h3>
                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                        {{ $artisan->produits->count() }} produit(s)
                    </span>
                </div>

                @if($artisan->produits->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($artisan->produits as $produit)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <!-- Image du produit -->
                                <div class="aspect-square bg-gradient-to-br from-gray-200 to-gray-300">
                                    @if($produit->image_principale)
                                        <img src="{{ asset('storage/' . $produit->image_principale) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('images/products/default-product.svg') }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                
                                <!-- Informations du produit -->
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $produit->nom }}</h4>
                                    @if($produit->description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($produit->description, 80) }}</p>
                                    @endif
                                    
                                    <!-- Catégorie et tags -->
                                    <div class="flex items-center justify-between mb-3">
                                        @if($produit->categorie)
                                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                                {{ $produit->categorie }}
                                            </span>
                                        @endif
                                        <span class="text-lg font-bold text-blue-600">{{ $produit->prix_formate }}</span>
                                    </div>
                                    
                                    <!-- Matériau -->
                                    @if($produit->matiere)
                                        <p class="text-xs text-gray-500 mb-3">Matériau : {{ $produit->matiere }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-300 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit disponible</h3>
                        <p class="text-gray-500">Cet artisan n'a pas encore publié de produits.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
