@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier votre boutique</h1>
            <p class="text-gray-600">Mettez à jour les informations de votre boutique</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Informations de base</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de la boutique *</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $shop->nom) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $shop->description) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $shop->adresse) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                        <input type="text" name="ville" id="ville" value="{{ old('ville', $shop->ville) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                        <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal', $shop->code_postal) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                        <input type="text" name="pays" id="pays" value="{{ old('pays', $shop->pays) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $shop->telephone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $shop->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="taille" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'exposants *</label>
                        <select name="taille" id="taille" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="petite" {{ old('taille', $shop->taille) == 'petite' ? 'selected' : '' }}>1-5 exposants</option>
                            <option value="moyenne" {{ old('taille', $shop->taille) == 'moyenne' ? 'selected' : '' }}>6-15 exposants</option>
                            <option value="grande" {{ old('taille', $shop->taille) == 'grande' ? 'selected' : '' }}>16+ exposants</option>
                        </select>
                    </div>

                    <div>
                        <label for="specialites" class="block text-sm font-medium text-gray-700 mb-2">Spécialités</label>
                        <input type="text" name="specialites" id="specialites" value="{{ old('specialites', $shop->specialites) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="siret" class="block text-sm font-medium text-gray-700 mb-2">SIRET</label>
                        <input type="text" name="siret" id="siret" value="{{ old('siret', $shop->siret) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="tva" class="block text-sm font-medium text-gray-700 mb-2">Numéro de TVA</label>
                        <input type="text" name="tva" id="tva" value="{{ old('tva', $shop->tva) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Tarifs et commissions -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Tarifs et commissions</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="loyer_depot_vente" class="block text-sm font-medium text-gray-700 mb-2">Loyer dépôt-vente (€)</label>
                        <input type="number" name="loyer_depot_vente" id="loyer_depot_vente" step="0.01" min="0" value="{{ old('loyer_depot_vente', $shop->loyer_depot_vente) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="loyer_permanence" class="block text-sm font-medium text-gray-700 mb-2">Loyer permanence (€)</label>
                        <input type="number" name="loyer_permanence" id="loyer_permanence" step="0.01" min="0" value="{{ old('loyer_permanence', $shop->loyer_permanence) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="commission_depot_vente" class="block text-sm font-medium text-gray-700 mb-2">Commission dépôt-vente (%)</label>
                        <input type="number" name="commission_depot_vente" id="commission_depot_vente" step="0.01" min="0" max="100" value="{{ old('commission_depot_vente', $shop->commission_depot_vente) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="commission_permanence" class="block text-sm font-medium text-gray-700 mb-2">Commission permanence (%)</label>
                        <input type="number" name="commission_permanence" id="commission_permanence" step="0.01" min="0" max="100" value="{{ old('commission_permanence', $shop->commission_permanence) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="nb_permanences_mois_indicatif" class="block text-sm font-medium text-gray-700 mb-2">Nombre indicatif de permanences par mois (à titre indicatif)</label>
                    <input type="number" name="nb_permanences_mois_indicatif" id="nb_permanences_mois_indicatif" min="0" value="{{ old('nb_permanences_mois_indicatif', $shop->nb_permanences_mois_indicatif) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Réseaux sociaux et web -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Présence en ligne</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                        <input type="url" name="site_web" id="site_web" value="{{ old('site_web', $shop->site_web) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                        <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $shop->instagram_url) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="tiktok_url" class="block text-sm font-medium text-gray-700 mb-2">TikTok</label>
                        <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url', $shop->tiktok_url) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                        <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $shop->facebook_url) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Horaires et photo -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Autres informations</h2>
                
                <div class="mb-6">
                    <label for="horaires_ouverture" class="block text-sm font-medium text-gray-700 mb-2">Horaires d'ouverture</label>
                    <textarea name="horaires_ouverture" id="horaires_ouverture" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('horaires_ouverture', $shop->horaires_ouverture) }}</textarea>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Photo de la boutique</label>
                    @if($shop->photo)
                        <div class="mb-4">
                            <img src="{{ Storage::url($shop->photo) }}" alt="Photo actuelle de la boutique" class="w-32 h-32 object-cover rounded-lg">
                            <p class="text-sm text-gray-500 mt-2">Photo actuelle</p>
                        </div>
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Formats acceptés : JPEG, PNG, JPG, GIF. Taille max : 2MB</p>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
