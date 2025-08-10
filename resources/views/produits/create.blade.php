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
                <a href="{{ route('artisan.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nom du produit *
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        @error('nom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie *
                        </label>
                        <select id="categorie" name="categorie" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="meubles" {{ old('categorie') == 'meubles' ? 'selected' : '' }}>Meubles</option>
                            <option value="decoration" {{ old('categorie') == 'decoration' ? 'selected' : '' }}>Décoration</option>
                            <option value="bijoux" {{ old('categorie') == 'bijoux' ? 'selected' : '' }}>Bijoux</option>
                            <option value="vetements" {{ old('categorie') == 'vetements' ? 'selected' : '' }}>Vêtements</option>
                            <option value="ceramique" {{ old('categorie') == 'ceramique' ? 'selected' : '' }}>Céramique</option>
                            <option value="textile" {{ old('categorie') == 'textile' ? 'selected' : '' }}>Textile</option>
                            <option value="metal" {{ old('categorie') == 'metal' ? 'selected' : '' }}>Métal</option>
                            <option value="bois" {{ old('categorie') == 'bois' ? 'selected' : '' }}>Bois</option>
                            <option value="autre" {{ old('categorie') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('categorie')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Prix -->
                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prix (€) *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" id="prix" name="prix" value="{{ old('prix') }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    @error('prix')
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Matériaux utilisés
                    </label>
                    <div class="space-y-2" id="materiaux-container">
                        <div class="flex gap-2">
                            <input type="text" name="materiaux[]" placeholder="Ex: Bois de chêne"
                                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                            <button type="button" onclick="addMateriau()" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dimensions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Dimensions (cm)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="largeur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Largeur</label>
                            <input type="number" id="largeur" name="dimensions[largeur]" value="{{ old('dimensions.largeur') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="hauteur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Hauteur</label>
                            <input type="number" id="hauteur" name="dimensions[hauteur]" value="{{ old('dimensions.hauteur') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="profondeur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Profondeur</label>
                            <input type="number" id="profondeur" name="dimensions[profondeur]" value="{{ old('dimensions.profondeur') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Couleur -->
                <div>
                    <label for="couleur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Couleur dominante
                    </label>
                    <input type="text" id="couleur" name="couleur" value="{{ old('couleur') }}" placeholder="Ex: Brun naturel"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Instructions d'entretien -->
                <div>
                    <label for="instructions_entretien" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instructions d'entretien
                    </label>
                    <textarea id="instructions_entretien" name="instructions_entretien" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">{{ old('instructions_entretien') }}</textarea>
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
                    <a href="{{ route('artisan.dashboard') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
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
function addMateriau() {
    const container = document.getElementById('materiaux-container');
    const newDiv = document.createElement('div');
    newDiv.className = 'flex gap-2';
    newDiv.innerHTML = `
        <input type="text" name="materiaux[]" placeholder="Ex: Bois de chêne"
               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
        <button type="button" onclick="removeMateriau(this)" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(newDiv);
}

function removeMateriau(button) {
    button.parentElement.remove();
}

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
