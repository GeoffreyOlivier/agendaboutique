@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if($hasBoutique)
        <!-- Tableau de bord complet pour les utilisateurs avec boutique -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Tableau de bord Boutique
            </h1>
            <p class="text-gray-600">
                Gérez votre boutique, la caisse et les demandes d'artisans.
            </p>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ventes</p>
                        <p class="text-2xl font-semibold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Chiffre d'affaires</p>
                        <p class="text-2xl font-semibold text-gray-900">0 €</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Artisans</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $artisans->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Demandes actives</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $demandes->where('statut', 'en_attente')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions principales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gestion des demandes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Demandes d'artisans</h3>
                
                @if($demandes->count() > 0)
                    <div class="space-y-4">
                        @foreach($demandes->take(5) as $demande)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900">{{ $demande->titre }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($demande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($demande->statut === 'acceptee') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($demande->statut) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($demande->description, 100) }}</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $demande->artisan->nom_artisan }}</span>
                                <span>{{ $demande->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir toutes les demandes →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune demande pour le moment</p>
                @endif
                
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouvelle demande
                    </a>
                </div>
            </div>

            <!-- Artisans associés -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Artisans associés</h3>
                
                @if($artisans->count() > 0)
                    <div class="space-y-4">
                        @foreach($artisans->take(5) as $artisan)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900">{{ $artisan->nom_artisan }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Actif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($artisan->description, 80) }}</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $artisan->specialites_liste }}</span>
                                <span>{{ $artisan->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('shop.artisans') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir tous les artisans →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun artisan associé pour le moment</p>
                @endif
                
                <div class="mt-6">
                    <a href="{{ route('shop.artisans') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Voir les artisans
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Actions rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="text-blue-500 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900">Nouvelle demande</h4>
                    <p class="text-sm text-gray-500">Créer une demande d'artisan</p>
                </a>

                <a href="{{ route('shop.artisans') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="text-green-500 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900">Voir tous les artisans</h4>
                    <p class="text-sm text-gray-500">Gérer les artisans associés</p>
                </a>

                <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="text-purple-500 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900">Caisse</h4>
                    <p class="text-sm text-gray-500">Gérer les ventes</p>
                </a>

                <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="text-orange-500 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900">Paramètres</h4>
                    <p class="text-sm text-gray-500">Configurer la boutique</p>
                </a>
            </div>
        </div>
    @else
        <!-- Message d'encouragement pour créer sa boutique -->
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Bienvenue dans votre espace Boutique !
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Vous avez le rôle de propriétaire de boutique. Pour commencer à utiliser toutes les fonctionnalités, 
                    vous devez créer votre première boutique.
                </p>
            </div>

            <!-- Fonctionnalités disponibles -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                    🚀 Que pourrez-vous faire avec votre boutique ?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Gestion des demandes d'artisans</h3>
                                <p class="text-sm text-gray-600">Publiez des demandes et trouvez les artisans qualifiés pour vos projets</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Caisse et gestion des ventes</h3>
                                <p class="text-sm text-gray-600">Suivez vos ventes et gérez votre comptabilité</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Réseau d'artisans</h3>
                                <p class="text-sm text-gray-600">Connectez-vous avec des artisans qualifiés et élargissez votre réseau</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Tableau de bord complet</h3>
                                <p class="text-sm text-gray-600">Suivez vos performances et gérez tous vos projets en un seul endroit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                               <!-- Bouton d'action principal -->
                   <div class="space-y-4">
                       <a href="{{ route('boutiques.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                           <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                           </svg>
                           Créer votre première boutique
                       </a>
                <p class="text-sm text-gray-500">
                    La création de votre boutique ne prend que quelques minutes et vous donne accès à toutes les fonctionnalités
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
