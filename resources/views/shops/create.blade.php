@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Créer votre boutique
            </h1>
            <p class="text-gray-600">
                Remplissez ce formulaire pour créer votre première boutique et commencer à utiliser toutes les fonctionnalités.
            </p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('shop.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Informations de base -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de base</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de la boutique *
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        <div>
                            <label for="taille" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre d'exposants *
                            </label>
                            <select name="taille" id="taille" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionnez le nombre d'exposants</option>
                                <option value="petite" {{ old('taille') == 'petite' ? 'selected' : '' }}>1-5 exposants</option>
                                <option value="moyenne" {{ old('taille') == 'moyenne' ? 'selected' : '' }}>6-15 exposants</option>
                                <option value="grande" {{ old('taille') == 'grande' ? 'selected' : '' }}>16+ exposants</option>
                            </select>
                            @error('taille')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('telephone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email de la boutique
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description de la boutique
                        </label>
                        <textarea name="description" id="description" rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Adresse</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse *
                            </label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('adresse')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Code postal *
                                </label>
                                <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('code_postal')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ville *
                                </label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ville')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pays *
                                </label>
                                <input type="text" name="pays" id="pays" value="{{ old('pays', 'France') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('pays')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conditions financières -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Conditions financières</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="loyer_depot_vente" class="block text-sm font-medium text-gray-700 mb-2">
                                Loyer dépôt-vente (€/mois)
                            </label>
                            <input type="number" name="loyer_depot_vente" id="loyer_depot_vente" value="{{ old('loyer_depot_vente') }}" 
                                step="0.01" min="0" max="999999.99"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('loyer_depot_vente')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loyer_permanence" class="block text-sm font-medium text-gray-700 mb-2">
                                Loyer permanence (€/mois)
                            </label>
                            <input type="number" name="loyer_permanence" id="loyer_permanence" value="{{ old('loyer_permanence') }}" 
                                step="0.01" min="0" max="999999.99"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('loyer_permanence')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="commission_depot_vente" class="block text-sm font-medium text-gray-700 mb-2">
                                Commission dépôt-vente (%)
                            </label>
                            <input type="number" name="commission_depot_vente" id="commission_depot_vente" value="{{ old('commission_depot_vente') }}" 
                                step="0.01" min="0" max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('commission_depot_vente')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="commission_permanence" class="block text-sm font-medium text-gray-700 mb-2">
                                Commission permanence (%)
                            </label>
                            <input type="number" name="commission_permanence" id="commission_permanence" value="{{ old('commission_permanence') }}" 
                                step="0.01" min="0" max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('commission_permanence')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nb_permanences_mois_indicatif" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de permanences par mois (indicatif)
                            </label>
                            <input type="number" name="nb_permanences_mois_indicatif" id="nb_permanences_mois_indicatif" value="{{ old('nb_permanences_mois_indicatif') }}" 
                                min="1" max="31"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">À titre indicatif pour les artisans</p>
                            @error('nb_permanences_mois_indicatif')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Présence en ligne</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">
                                Site web
                            </label>
                            <input type="url" name="site_web" id="site_web" value="{{ old('site_web') }}" 
                                placeholder="https://www.votresite.com"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('site_web')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Instagram
                            </label>
                            <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url') }}" 
                                placeholder="https://www.instagram.com/votrecompte"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('instagram_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tiktok_url" class="block text-sm font-medium text-gray-700 mb-2">
                                TikTok
                            </label>
                            <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url') }}" 
                                placeholder="https://www.tiktok.com/@votrecompte"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tiktok_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Facebook
                            </label>
                            <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url') }}" 
                                placeholder="https://www.facebook.com/votrepage"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('facebook_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations complémentaires</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="siret" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro SIRET
                            </label>
                            <input type="text" name="siret" id="siret" value="{{ old('siret') }}" 
                                maxlength="14"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('siret')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tva" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de TVA
                            </label>
                            <input type="text" name="tva" id="tva" value="{{ old('tva') }}" 
                                maxlength="20"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tva')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="horaires_ouverture" class="block text-sm font-medium text-gray-700 mb-2">
                            Horaires d'ouverture
                        </label>
                        <textarea name="horaires_ouverture" id="horaires_ouverture" rows="3" 
                            placeholder="Ex: Lundi-Vendredi: 9h-18h, Samedi: 9h-17h, Dimanche: Fermé"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('horaires_ouverture') }}</textarea>
                        @error('horaires_ouverture')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('dashboard') }}" 
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Créer ma boutique
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
