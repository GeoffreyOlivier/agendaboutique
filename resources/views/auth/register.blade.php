@extends('layouts.app-no-sidebar')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Créer un compte
            </h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="name" class="sr-only">Nom complet</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Nom complet">
                </div>
                <div>
                    <label for="email" class="sr-only">Adresse email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Adresse email">
                </div>
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Mot de passe">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Confirmer le mot de passe">
                </div>
            </div>

            <!-- Sélection du rôle -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 text-center">
                    Choisissez votre profil
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="radio" id="role_shop" name="role" value="shop" class="sr-only" required>
                        <label for="role_shop" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Boutique</span>
                            <span class="text-xs text-gray-500 text-center">Gérer ma boutique</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" id="role_craftsman" name="role" value="craftsman" class="sr-only" required>
                        <label for="role_craftsman" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 transition-colors duration-200">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Artisan</span>
                            <span class="text-xs text-gray-500 text-center">Présenter mes créations</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    S'inscrire
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Déjà un compte ? Se connecter
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion de la sélection visuelle des rôles
document.querySelectorAll('input[name="role"]').forEach(input => {
    input.addEventListener('change', function() {
        // Retirer la sélection de tous les labels
        document.querySelectorAll('label[for^="role_"]').forEach(label => {
            label.classList.remove('border-blue-500', 'border-green-500', 'bg-blue-50', 'bg-green-50');
            label.classList.add('border-gray-200');
        });
        
        // Ajouter la sélection au label choisi
        const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
        if (this.value === 'shop') {
            selectedLabel.classList.remove('border-gray-200');
            selectedLabel.classList.add('border-blue-500', 'bg-blue-50');
        } else if (this.value === 'craftsman') {
            selectedLabel.classList.remove('border-gray-200');
            selectedLabel.classList.add('border-green-500', 'bg-green-50');
        }
    });
});
</script>
@endsection
