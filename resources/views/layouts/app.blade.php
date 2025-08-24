<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Boutique - @yield('title', 'Dashboard')</title>
    
    <!-- Tailwind CSS est maintenant inclus dans les assets compilés -->
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Assets Vite avec Tailwind CSS et Alpine.js -->
    @filamentStyles
    @livewireStyles
    @vite(['resources/themes/anchor/assets/css/app.css', 'resources/themes/anchor/assets/js/app.js'])
    
    <!-- CSS personnalisé pour Wirechat -->
    <link rel="stylesheet" href="{{ asset('css/wirechat-custom.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    <!-- La configuration Tailwind est maintenant dans tailwind.config.js -->
    
    <!-- Used to add dark mode right away, adding here prevents any flicker -->
    <script>
        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem('theme') && localStorage.getItem('theme') == 'dark'){
                document.documentElement.classList.add('dark');
            }
        }
        
        // Solution spécifique pour le problème de dropdown avec Wirechat
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre que Alpine soit complètement initialisé
            let attempts = 0;
            const maxAttempts = 50;
            
            function initDropdown() {
                attempts++;
                
                if (window.Alpine && window.Alpine.data) {
                    // Force la réinitialisation du dropdown après l'initialisation d'Alpine
                    setTimeout(() => {
                        const dropdownElement = document.querySelector('[x-data*="sidebarDropup"]');
                        if (dropdownElement && window.Alpine.initTree) {
                            window.Alpine.initTree(dropdownElement);
                        }
                    }, 100);
                } else if (attempts < maxAttempts) {
                    // Retry si Alpine n'est pas encore prêt
                    setTimeout(initDropdown, 50);
                }
            }
            
            initDropdown();
        });
        
        // Fonction globale pour le dropdown de sidebar
        window.sidebarDropup = function() {
            return {
                dropupOpen: false,
                
                toggleDropup() {
                    this.dropupOpen = !this.dropupOpen;
                    console.log('Dropdown toggled:', this.dropupOpen);
                },
                
                closeDropup() {
                    this.dropupOpen = false;
                    console.log('Dropdown closed');
                },
                
                init() {
                    console.log('Sidebar dropdown initialized');
                    
                    // Écouter les clics globaux pour fermer le dropdown
                    document.addEventListener('click', (e) => {
                        if (!this.$el.contains(e.target) && this.dropupOpen) {
                            this.dropupOpen = false;
                        }
                    });
                    
                    // Fermer avec Escape
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && this.dropupOpen) {
                            this.dropupOpen = false;
                        }
                    });
                }
            }
        }
    </script>
</head>
<body x-data class="flex flex-col lg:min-h-screen bg-zinc-50 dark:bg-zinc-900">

    <!-- Sidebar -->
    <div x-data="{ sidebarOpen: false }"  @open-sidebar.window="sidebarOpen = true"
        x-init="
            $watch('sidebarOpen', function(value){
                if(value){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
            });
        "
        class="relative z-50 w-screen md:w-auto" x-cloak>
        {{-- Backdrop for mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
        
        {{-- Sidebar --}} 
        <div :class="{ '-translate-x-full': !sidebarOpen }"
            class="fixed top-0 left-0 flex items-stretch -translate-x-full overflow-hidden lg:translate-x-0 z-50 h-dvh md:h-screen transition-[width,transform] duration-150 ease-out bg-zinc-50 dark:bg-zinc-900 w-64 group">  
            <div class="flex flex-col justify-between w-full overflow-auto md:h-full h-svh pt-4 pb-2.5">
                <div class="relative flex flex-col">
                    <button x-on:click="sidebarOpen=false" class="flex items-center justify-center flex-shrink-0 w-10 h-10 ml-4 rounded-md lg:hidden text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 dark:hover:bg-zinc-700/70 hover:bg-gray-200/70">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="flex items-center px-5 space-x-2">
                        <a href="/" class="flex justify-center items-center py-4 pl-0.5 space-x-1 font-bold text-zinc-900 dark:text-zinc-100">
                            <span class="text-xl font-bold">Agenda Boutique</span>
                        </a>
                    </div>
                    <div class="flex items-center px-4 pt-1 pb-3">
                        <div class="relative flex items-center w-full h-full rounded-lg">
                            <svg class="absolute left-0 w-5 h-5 ml-2 text-gray-400 -translate-y-px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" class="w-full py-2 pl-8 text-sm border rounded-lg bg-zinc-200/70 focus:bg-white duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400" placeholder="Rechercher">
                        </div>
                    </div>

                    <div class="flex flex-col justify-start items-center px-4 space-y-1.5 w-full h-full text-slate-600 dark:text-zinc-400">
                        <a href="{{ route('dashboard') }}" class="@if(Request::is('dashboard')){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span class="flex-shrink-0 ease-out duration-50">Dashboard</span>
                        </a>
                        
                        @auth
                            @if(auth()->user()->isShop())
                            <a href="{{ route('shop.dashboard') }}" class="@if(Request::is('shop/*')){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Boutique</span>
                            </a>
                            
                            <!-- Lien Caisse pour les boutiques -->
                            <a href="#" class="border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-green-100 dark:hover:bg-green-900/30 justify-start items-center hover:text-green-700 dark:hover:text-green-300 space-x-2 overflow-hidden group-hover:autoflow-auto items bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Caisse</span>
                            </a>
                            @endif
                            
                            @if(auth()->user()->isArtisan())
                            <a href="{{ route('artisan.dashboard') }}" class="@if(Request::is('artisan/*')){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Atelier</span>
                            </a>
                            
                            <a href="{{ route('produits.index') }}" class="@if(Request::is('produits/*')){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Mes produits</span>
                            </a>
                            @endif
                        @endauth
                        
                        <a href="#" class="border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="flex-shrink-0 ease-out duration-50">Agenda</span>
                        </a>
                        <a href="/chats" class="@if(Request::is('chats*')){{ 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-sm bg-white font-medium dark:border-white dark:bg-zinc-700/60 dark:text-zinc-100' }}@else{{ 'border-transparent' }}@endif transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="flex-shrink-0 ease-out duration-50">Messages</span>
                        </a>
                        
                        <!-- Lien Artisans - visible seulement pour les boutiques, pas pour les artisans -->
                        @auth
                            @if(auth()->user()->isShop() && !auth()->user()->isArtisanOnly())
                            <a href="{{ route('shop.artisans') }}" class="border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2 overflow-hidden group-hover:autoflow-auto items">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Artisans</span>
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="relative px-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
                    @auth
                        <div class="flex items-center px-2.5 py-2 space-x-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="flex-shrink-0 ease-out duration-50">Déconnexion</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="flex-shrink-0 ease-out duration-50">Connexion</span>
                        </a>
                    @endauth

                    <!-- Dropup avec mode sombre et autres boutons -->
                    <div x-data="sidebarDropup()" x-id="['dropdown']" class="relative">
                        <button @click="toggleDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="flex-shrink-0 ease-out duration-50">Paramètres</span>
                            <svg class="flex-shrink-0 w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="dropupOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute bottom-full left-0 mb-2 w-full bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 z-[60]">
                            
                            <!-- Mode sombre -->
                            <div class="p-2">
                                <button @click="
                                    const html = document.documentElement;
                                    const isDark = html.classList.contains('dark');
                                    if (isDark) {
                                        html.classList.remove('dark');
                                        localStorage.setItem('theme', 'light');
                                    } else {
                                        html.classList.add('dark');
                                        localStorage.setItem('theme', 'dark');
                                    }
                                    closeDropup();
                                " class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                    <span class="flex-shrink-0 ease-out duration-50">Mode sombre</span>
                                </button>
                            </div>

                            <!-- Paramètres Boutique -->
                            @if(auth()->user()->isShop() && auth()->user()->boutique)
                            <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                                <a href="{{ route('boutiques.edit', auth()->user()->boutique->id) }}" @click="closeDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="flex-shrink-0 ease-out duration-50">Paramètres Boutique</span>
                                </a>
                            </div>
                            @endif
                            
                            <!-- Switch d'interface pour les utilisateurs avec les deux rôles -->
                            @auth
                                @if(auth()->user()->isShopAndArtisan())
                                <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                                    <form method="POST" action="{{ route('switch.interface') }}" class="w-full">
                                        @csrf
                                        <button type="submit" @click="closeDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                            <span class="flex-shrink-0 ease-out duration-50">
                                                @if(session('current_interface') === 'artisan')
                                                    Passer à l'interface Boutique
                                                @else
                                                    Passer à l'interface Artisan
                                                @endif
                                            </span>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endauth
                            
                            <!-- Documentation -->
                            <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                                <a href="#" @click="closeDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="flex-shrink-0 ease-out duration-50">Documentation</span>
                                </a>
                            </div>
                            
                            <!-- Support -->
                            <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                                <a href="#" @click="closeDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="flex-shrink-0 ease-out duration-50">Support</span>
                                </a>
                            </div>
                            
                            <!-- Changelog -->
                            <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                                <a href="#" @click="closeDropup()" class="w-full border-transparent transition-colors border px-2.5 py-2 flex rounded-lg text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 space-x-2">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="flex-shrink-0 ease-out duration-50">Changelog</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col min-h-screen pl-0 justify-stretch lg:pl-64">
        {{-- Mobile Header --}}
        <header class="lg:hidden px-5 block flex justify-between sticky top-0 z-40 bg-gray-50 dark:bg-zinc-900 -mb-px border-b border-zinc-200/70 dark:border-zinc-700 h-[72px] items-center">
            <button x-on:click="window.dispatchEvent(new CustomEvent('open-sidebar'))" class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-md text-zinc-700 dark:text-zinc-200 hover:bg-gray-200/70 dark:hover:bg-zinc-700/70">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
            </button>
            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">Connexion</a>
                @endauth
            </div>
        </header>
        {{-- End Mobile Header --}}
        
        <main class="flex flex-col flex-1 xl:px-0 lg:pt-4 lg:h-screen">
            <div class="flex-1 h-full overflow-hidden bg-white border-t border-l-0 lg:border-l dark:bg-zinc-800 lg:rounded-tl-xl border-zinc-200/70 dark:border-zinc-700">
                <div class="w-full h-full px-5 sm:px-8 lg:overflow-y-scroll scrollbar-hidden lg:pt-5 lg:px-5">
                    <!-- Messages flash -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Contenu principal -->
                    @yield('content')
                    @isset($slot)
                        {{ $slot }}
                    @endisset
                </div>
            </div>
        </main>
    </div>
    
    @livewireScripts
    @filamentScripts
</body>
</html>
