@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            Bienvenue {{ $user->name }}
        </h1>
        <p class="text-gray-600">
            Choisissez votre profil pour accéder aux fonctionnalités appropriées.
        </p>
    </div>

    <!-- Sélection de profil -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-blue-500">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Propriétaire de Boutique</h3>
                <p class="text-gray-600 mb-6">
                    Gérez votre boutique, la caisse et les demandes d'artisans.
                </p>
                <form method="POST" action="{{ route('assign.shop.role') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Devenir Boutique
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-green-500">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Artisan</h3>
                <p class="text-gray-600 mb-6">
                    Présentez vos créations et répondez aux demandes des boutiques.
                </p>
                <form method="POST" action="{{ route('assign.artisan.role') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        Devenir Artisan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Option pour les deux rôles -->
    <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-purple-500">
        <div class="text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Boutique + Artisan</h3>
            <p class="text-gray-600 mb-6">
                Vous êtes propriétaire de boutique ET artisan ? Accédez aux deux interfaces.
            </p>
            <form method="POST" action="{{ route('assign.both.roles') }}" class="inline">
                @csrf
                <button type="submit" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                    Activer les deux profils
                </button>
            </form>
        </div>
    </div>

    <!-- Informations -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">Comment ça marche ?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-800">
            <div>
                <h4 class="font-medium mb-1">1. Choisissez votre profil</h4>
                <p>Sélectionnez le profil qui correspond à votre activité principale.</p>
            </div>
            <div>
                <h4 class="font-medium mb-1">2. Configurez votre compte</h4>
                <p>Complétez les informations de votre boutique ou de votre atelier.</p>
            </div>
            <div>
                <h4 class="font-medium mb-1">3. Commencez à utiliser</h4>
                <p>Accédez aux fonctionnalités adaptées à votre profil.</p>
            </div>
        </div>
    </div>
</div>
@endsection
