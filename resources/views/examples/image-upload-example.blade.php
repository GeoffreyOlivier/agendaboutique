@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Exemple d'upload d'images avec validation</h1>
    
    <!-- Section Boutique -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Photo de boutique</h2>
        
        <!-- Aide à l'upload -->
        <x-image-upload-help type="boutique" />
        
        <!-- Formulaire d'exemple -->
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Photo de votre boutique
                </label>
                <input type="file" 
                       name="photo" 
                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">
                    Formats acceptés : JPEG, PNG, JPG, GIF, WebP • Taille max : 2 MB
                </p>
            </div>
        </form>
    </div>
    
    <!-- Section Produit -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Images de produit</h2>
        
        <!-- Aide à l'upload -->
        <x-image-upload-help type="produit" />
        
        <!-- Formulaire d'exemple -->
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Images de votre produit
                </label>
                <input type="file" 
                       name="images[]" 
                       multiple
                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">
                    Vous pouvez sélectionner plusieurs images • Formats acceptés : JPEG, PNG, JPG, GIF, WebP • Taille max : 2 MB par image
                </p>
            </div>
        </form>
    </div>
    
    <!-- Section Erreurs -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Exemple d'affichage des erreurs</h2>
        
        <!-- Simulation d'erreurs -->
        @php
            $fakeErrors = new Illuminate\Support\ViewErrorBag();
            $fakeErrors->put('images', [
                'L\'image "photo1.jpg" est trop lourde (3.2 MB). Taille maximale : 2 MB',
                'L\'image "photo2.gif" n\'est pas dans un format accepté'
            ]);
        @endphp
        
        <x-image-validation-errors :errors="$fakeErrors" field="images" />
    </div>
</div>
@endsection
