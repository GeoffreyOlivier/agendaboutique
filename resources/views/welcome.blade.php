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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative h-48 bg-gradient-to-br from-blue-400 to-blue-600">
                <!-- Image temporaire pour "Pour les boutiques" -->
                <div class="absolute inset-0 bg-blue-900 bg-opacity-40"></div>
                <div class="relative top-4 right-4 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center cursor-help" style="position: absolute; top: 1rem; right: 1rem;">
                    <x-phosphor-question-mark class="text-white text-lg" />
                    <!-- Modale volante au survol -->
                    <div class="tooltip-content absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-sm rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 pointer-events-none z-10" style="position: absolute; bottom: 100%; right: 0; margin-bottom: 0.5rem; width: 16rem; padding: 0.75rem; background-color: #111827; color: white; font-size: 0.875rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); opacity: 0; visibility: hidden; transition: all 0.2s; pointer-events: none; z-index: 10;">
                        <div class="relative">
                            <p class="mb-2">Accédez à votre espace boutique pour :</p>
                            <ul class="text-xs space-y-1">
                                <li>• Gérer vos products et services</li>
                                <li>• Trouver des artisans qualifiés</li>
                                <li>• Créer des demandes personnalisées</li>
                                <li>• Suivre vos collaborations</li>
                            </ul>
                            <!-- Flèche vers le bas -->
                            <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900" style="position: absolute; top: 100%; right: 1rem; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #111827;"></div>
                        </div>
                    </div>
                </div>
                <!-- Icône temporaire représentant une boutique -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pour les boutiques</h3>
                <p class="text-gray-600 mb-4">
                    Gérez votre boutique, trouvez des artisans talentueux et créez des demandes personnalisées.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Accéder à ma boutique
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative h-48 bg-gradient-to-br from-green-400 to-green-600">
                <!-- Image temporaire pour "Pour les artisans" -->
                <div class="absolute inset-0 bg-green-900 bg-opacity-40"></div>
                <div class="relative top-4 right-4 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center cursor-help" style="position: absolute; top: 1rem; right: 1rem;">
                    <x-phosphor-question-mark class="text-white text-lg" />
                    <!-- Modale volante au survol -->
                    <div class="tooltip-content absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-sm rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 pointer-events-none z-10" style="position: absolute; bottom: 100%; right: 0; margin-bottom: 0.5rem; width: 16rem; padding: 0.75rem; background-color: #111827; color: white; font-size: 0.875rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); opacity: 0; visibility: hidden; transition: all 0.2s; pointer-events: none; z-index: 10;">
                        <div class="relative">
                            <p class="mb-2">Accédez à votre espace craftsman pour :</p>
                            <ul class="text-xs space-y-1">
                                <li>• Présenter vos créations</li>
                                <li>• Répondre aux demandes des boutiques</li>
                                <li>• Développer votre réseau</li>
                                <li>• Gérer vos commandes</li>
                            </ul>
                            <!-- Flèche vers le bas -->
                            <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900" style="position: absolute; top: 100%; right: 1rem; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #111827;"></div>
                        </div>
                    </div>
                </div>
                <!-- Icône temporaire représentant un craftsman -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pour les artisans</h3>
                <p class="text-gray-600 mb-4">
                    Présentez vos créations, répondez aux demandes des boutiques et développez votre activité.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                    Accéder à mon atelier
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative h-48 bg-gradient-to-br from-purple-400 to-purple-600">
                <!-- Image temporaire pour "Pour craftsman & Boutique" -->
                <div class="absolute inset-0 bg-purple-900 bg-opacity-40"></div>
                <div class="relative top-4 right-4 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center cursor-help" style="position: absolute; top: 1rem; right: 1rem;">
                    <x-phosphor-question-mark class="text-white text-lg" />
                    <!-- Modale volante au survol -->
                    <div class="tooltip-content absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-sm rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 pointer-events-none z-10" style="position: absolute; bottom: 100%; right: 0; margin-bottom: 0.5rem; width: 16rem; padding: 0.75rem; background-color: #111827; color: white; font-size: 0.875rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); opacity: 0; visibility: hidden; transition: all 0.2s; pointer-events: none; z-index: 10;">
                        <div class="relative">
                            <p class="mb-2">Accédez à votre espace combiné pour :</p>
                            <ul class="text-xs space-y-1">
                                <li>• Gérer vos deux activités</li>
                                <li>• Maximiser vos collaborations</li>
                                <li>• Bénéficier des deux interfaces</li>
                                <li>• Optimiser votre croissance</li>
                            </ul>
                            <!-- Flèche vers le bas -->
                            <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900" style="position: absolute; top: 100%; right: 1rem; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #111827;"></div>
                        </div>
                    </div>
                </div>
                <!-- Icône temporaire représentant craftsman + boutique -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pour craftsman & Boutique</h3>
                <p class="text-gray-600 mb-4">
                    Combinez les deux rôles et maximisez vos opportunités de collaboration et de croissance.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                    Accéder à mon espace
                </a>
            </div>
        </div>
    </div>

    <style>
        .tooltip-content {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease-in-out;
        }
        
        .tooltip-content:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Survol du parent pour afficher la tooltip */
        .tooltip-content:hover,
        .tooltip-content:hover ~ .tooltip-content,
        .tooltip-content:hover + .tooltip-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Alternative avec JavaScript */
        .tooltip-trigger:hover .tooltip-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>

    <script>
        // JavaScript pour assurer le fonctionnement du survol
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggers = document.querySelectorAll('.tooltip-content').forEach(function(tooltip) {
                const trigger = tooltip.parentElement;
                
                trigger.addEventListener('mouseenter', function() {
                    tooltip.style.opacity = '1';
                    tooltip.style.visibility = 'visible';
                    tooltip.style.transform = 'translateY(0)';
                });
                
                trigger.addEventListener('mouseleave', function() {
                    tooltip.style.opacity = '0';
                    tooltip.style.visibility = 'hidden';
                    tooltip.style.transform = 'translateY(10px)';
                });
            });
        });
    </script>

    <!-- Section Découvrir les products -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-md p-8 mb-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Découvrez nos créations</h2>
            <p class="text-gray-600 mb-6">Explorez notre galerie de products artisanaux uniques</p>
            <a href="{{ route('products.public') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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
                <p class="text-sm text-gray-600">Gérez vos demandes et products en toute simplicité</p>
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
