@extends('layouts.app')

@section('title', 'Modifier le produit')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Modifier le produit</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Modifiez les informations de votre produit</p>
                </div>
                <a href="{{ route('produits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form action="{{ route('produits.update', $produit) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nom du produit *
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required
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
                            <option value="meubles" {{ (old('categorie', $produit->categorie) == 'meubles') ? 'selected' : '' }}>Meubles</option>
                            <option value="decoration" {{ (old('categorie', $produit->categorie) == 'decoration') ? 'selected' : '' }}>Décoration</option>
                            <option value="bijoux" {{ (old('categorie', $produit->categorie) == 'bijoux') ? 'selected' : '' }}>Bijoux</option>
                            <option value="vetements" {{ (old('categorie', $produit->categorie) == 'vetements') ? 'selected' : '' }}>Vêtements</option>
                            <option value="ceramique" {{ (old('categorie', $produit->categorie) == 'ceramique') ? 'selected' : '' }}>Céramique</option>
                            <option value="textile" {{ (old('categorie', $produit->categorie) == 'textile') ? 'selected' : '' }}>Textile</option>
                            <option value="metal" {{ (old('categorie', $produit->categorie) == 'metal') ? 'selected' : '' }}>Métal</option>
                            <option value="bois" {{ (old('categorie', $produit->categorie) == 'bois') ? 'selected' : '' }}>Bois</option>
                            <option value="autre" {{ (old('categorie', $produit->categorie) == 'autre') ? 'selected' : '' }}>Autre</option>
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
                        <input type="number" id="prix" name="prix" value="{{ old('prix', $produit->prix_base) }}" step="0.01" min="0" required
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
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">{{ old('description', $produit->description) }}</textarea>
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
                        @if($produit->tags && count($produit->tags) > 0)
                            @foreach($produit->tags as $index => $materiau)
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="materiaux[]" value="{{ $materiau }}" 
                                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                                    <button type="button" onclick="removeMateriau(this)" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addMateriau()" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un matériau
                    </button>
                    @error('materiaux')
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
                            <label for="largeur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Largeur</label>
                            <input type="number" id="largeur" name="dimensions[largeur]" value="{{ old('dimensions.largeur', $produit->dimensions['largeur'] ?? '') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="hauteur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Hauteur</label>
                            <input type="number" id="hauteur" name="dimensions[hauteur]" value="{{ old('dimensions.hauteur', $produit->dimensions['hauteur'] ?? '') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="profondeur" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Profondeur</label>
                            <input type="number" id="profondeur" name="dimensions[profondeur]" value="{{ old('dimensions.profondeur', $produit->dimensions['profondeur'] ?? '') }}" step="0.1" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    @error('dimensions.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleur -->
                <div>
                    <label for="couleur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Couleur
                    </label>
                    <input type="text" id="couleur" name="couleur" value="{{ old('couleur', $produit->matiere) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    @error('couleur')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instructions d'entretien -->
                <div>
                    <label for="instructions_entretien" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instructions d'entretien
                    </label>
                    <textarea id="instructions_entretien" name="instructions_entretien" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">{{ old('instructions_entretien', $produit->instructions_entretien) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Conseils pour l'entretien et la conservation du produit</p>
                    @error('instructions_entretien')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Images existantes -->
                @if($produit->images && count($produit->images) > 0)
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Images actuelles
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="images-container">
                        @foreach($produit->images as $index => $image)
                        <div class="relative image-item" data-index="{{ $index }}" data-image="{{ $image }}">
                            <img src="{{ asset('storage/' . $image) }}" alt="Image du produit" class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute top-2 right-2">
                                <button type="button" onclick="deleteImage(this)" class="cursor-pointer bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Cliquez sur la croix rouge pour supprimer une image</p>
                </div>
                @endif
                
                <!-- Inputs cachés pour les images à supprimer -->
                <div id="deleted-images-container"></div>

                <!-- Nouvelles images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ajouter de nouvelles images
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 mt-1">Formats acceptés : JPEG, PNG, JPG, GIF. Taille max : 2MB par image</p>
                    
                    <!-- Prévisualisation des nouvelles images -->
                    <div id="new-images-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('produits.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addMateriau() {
    const container = document.getElementById('materiaux-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="materiaux[]" 
               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
        <button type="button" onclick="removeMateriau(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeMateriau(button) {
    button.parentElement.remove();
}

// Gestion de la prévisualisation des nouvelles images
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('new-images-preview');
    preview.innerHTML = '';
    
    if (e.target.files.length > 0) {
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative new-image-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Prévisualisation" class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-600">
                    <div class="absolute top-2 right-2">
                        <button type="button" onclick="removeNewImage(this, ${index})" class="cursor-pointer bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-1 text-xs text-gray-500 text-center truncate">${file.name}</div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
});

function removeNewImage(button, index) {
    const imageItem = button.closest('.new-image-item');
    
    // Animation de suppression
    imageItem.style.transition = 'all 0.3s ease';
    imageItem.style.opacity = '0';
    imageItem.style.transform = 'scale(0.8)';
    
    setTimeout(() => {
        imageItem.remove();
        
        // Supprimer le fichier de l'input file
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        const { files } = input;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        input.files = dt.files;
        
        // Vérifier s'il reste des images
        const remainingImages = document.querySelectorAll('.new-image-item');
        if (remainingImages.length === 0) {
            const preview = document.getElementById('new-images-preview');
            preview.innerHTML = '';
        }
    }, 300);
}

function deleteImage(button) {
    const imageItem = button.closest('.image-item');
    const index = imageItem.dataset.index;
    const image = imageItem.dataset.image;
    
    // Créer un input hidden pour l'image à supprimer
    const deletedContainer = document.getElementById('deleted-images-container');
    const deleteInput = document.createElement('input');
    deleteInput.type = 'hidden';
    deleteInput.name = 'delete_images[]';
    deleteInput.value = image;
    deleteInput.id = 'deleted_image_' + Date.now(); // ID unique
    deletedContainer.appendChild(deleteInput);
    
    // Ajouter une classe pour masquer l'image avec une animation
    imageItem.style.transition = 'all 0.3s ease';
    imageItem.style.opacity = '0';
    imageItem.style.transform = 'scale(0.8)';
    
    // Supprimer l'élément après l'animation
    setTimeout(() => {
        imageItem.remove();
        
        // Vérifier s'il reste des images
        const remainingImages = document.querySelectorAll('.image-item');
        if (remainingImages.length === 0) {
            const container = document.getElementById('images-container');
            if (container) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">Aucune image restante</p>';
            }
        }
    }, 300);
}
</script>
@endsection
