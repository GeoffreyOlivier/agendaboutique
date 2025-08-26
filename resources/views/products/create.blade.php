@extends('layouts.app')

@section('title', 'Nouveau produit')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Nouveau produit</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Ajoutez un nouveau produit à votre catalogue</p>
                </div>
                <a href="{{ route('craftsman.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nom du produit *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie *
                        </label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>Meubles</option>
                            <option value="decoration" {{ old('category') == 'decoration' ? 'selected' : '' }}>Décoration</option>
                            <option value="jewelry" {{ old('category') == 'jewelry' ? 'selected' : '' }}>Bijoux</option>
                            <option value="clothing" {{ old('category') == 'clothing' ? 'selected' : '' }}>Vêtements</option>
                            <option value="ceramic" {{ old('category') == 'ceramic' ? 'selected' : '' }}>Céramique</option>
                            <option value="textile" {{ old('category') == 'textile' ? 'selected' : '' }}>Textile</option>
                            <option value="metal" {{ old('category') == 'metal' ? 'selected' : '' }}>Métal</option>
                            <option value="wood" {{ old('category') == 'wood' ? 'selected' : '' }}>Bois</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Prix -->
                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prix (€) *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" id="base_price" name="base_price" value="{{ old('base_price') }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    @error('base_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description *
                    </label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Décrivez votre produit en détail (matériaux, techniques, etc.)</p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Matériaux -->
                <div>
                    <label for="material" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Matériaux utilisés
                    </label>
                    <input type="text" id="material" name="material" value="{{ old('material') }}" placeholder="Ex: Bois de chêne, Métal, Tissu"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 mt-1">Séparez les matériaux par des virgules</p>
                    @error('material')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dimensions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Dimensions (cm)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="width" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Largeur</label>
                            <input type="number" id="width" name="dimensions[width]" value="{{ old('dimensions.width') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="height" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Hauteur</label>
                            <input type="number" id="height" name="dimensions[height]" value="{{ old('dimensions.height') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="depth" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Profondeur</label>
                            <input type="number" id="depth" name="dimensions[depth]" value="{{ old('dimensions.depth') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tags (couleur, style, etc.)
                    </label>
                    <input type="text" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Ex: brun, naturel, vintage"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 mt-1">Séparez les tags par des virgules</p>
                </div>

                <!-- Instructions d'entretien -->
                <div>
                    <label for="care_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instructions d'entretien
                    </label>
                    <textarea id="care_instructions" name="care_instructions" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">{{ old('care_instructions') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Conseils pour l'entretien et la conservation du produit</p>
                </div>

                <!-- Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Photos du produit
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label for="images" class="cursor-pointer bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Sélectionner des images
                            </label>
                            <input id="images" name="images[]" type="file" multiple accept="image/*" class="hidden">
                        </div>
                        <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF jusqu'à 2MB par image</p>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('craftsman.dashboard') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Créer le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Les fonctions JavaScript pour les matériaux ont été simplifiées car on utilise maintenant un seul champ texte

// Prévisualisation des images
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

function removeImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    const { files } = input;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    document.getElementById('images').dispatchEvent(new Event('change'));
}
</script>
@endsection
