@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            Tableau de bord Artisan
        </h1>
        <p class="text-gray-600">
            Gérez vos produits et répondez aux demandes des boutiques.
        </p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Produits</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $produits->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Demandes reçues</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $demandes->where('statut', 'en_attente')->count() }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Revenus</p>
                    <p class="text-2xl font-semibold text-gray-900">0 €</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Boutiques</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Gestion des produits -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Mes produits</h3>
            
            @if($produits->count() > 0)
                <div class="space-y-4">
                    @foreach($produits->take(5) as $produit)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-900">{{ $produit->nom }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($produit->statut === 'disponible') bg-green-100 text-green-800
                                @elseif($produit->statut === 'en_commande') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($produit->statut) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($produit->description, 100) }}</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $produit->prix_formate }}</span>
                            <span>{{ $produit->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('produits.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Voir tous les produits →
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun produit pour le moment</p>
            @endif
            
            <div class="mt-6">
                <a href="{{ route('produits.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau produit
                </a>
            </div>
        </div>

        <!-- Demandes reçues -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Ventes réalisées</h3>
            
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
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($demande->description, 80) }}</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $demande->boutique->nom }}</span>
                            <span>{{ $demande->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir toutes les ventes →
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucune vente pour le moment</p>
            @endif
            @if($demandes->count() > 0)
            <div class="mt-6">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir les ventes
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('produits.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="text-green-500 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900">Nouveau produit</h4>
                <p class="text-sm text-gray-500">Ajouter un produit</p>
            </a>

            <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="text-blue-500 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900">Demandes</h4>
                <p class="text-sm text-gray-500">Voir les demandes</p>
            </a>

            <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="text-purple-500 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900">Portfolio</h4>
                <p class="text-sm text-gray-500">Gérer le portfolio</p>
            </a>

            <a href="#" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="text-orange-500 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900">Paramètres</h4>
                <p class="text-sm text-gray-500">Configurer le profil</p>
            </a>
        </div>
    </div>
</div>
@endsection
