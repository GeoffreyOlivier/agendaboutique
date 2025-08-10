@extends('layouts.app-no-sidebar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Bienvenue sur Agenda Boutique
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            La plateforme qui connecte les boutiques et les artisans pour créer ensemble des expériences uniques.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pour les Boutiques</h3>
                <p class="text-gray-600 mb-6">
                    Gérez votre boutique, trouvez des artisans talentueux et créez des demandes personnalisées.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Accéder à ma boutique
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pour les Artisans</h3>
                <p class="text-gray-600 mb-6">
                    Présentez vos créations, répondez aux demandes des boutiques et développez votre activité.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                    Accéder à mon atelier
                </a>
            </div>
        </div>
    </div>

    <!-- Section Découvrir les produits -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-md p-8 mb-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Découvrez nos créations</h2>
            <p class="text-gray-600 mb-6">Explorez notre galerie de produits artisanaux uniques</p>
            <a href="{{ route('produits.public') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Voir la galerie
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Fonctionnalités principales</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Communication</h4>
                <p class="text-sm text-gray-600">Échangez directement avec les boutiques et artisans</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Gestion simplifiée</h4>
                <p class="text-sm text-gray-600">Gérez vos demandes et produits en toute simplicité</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Rapidité</h4>
                <p class="text-sm text-gray-600">Trouvez rapidement les partenaires idéaux</p>
            </div>
        </div>
    </div>
</div>
@endsection
