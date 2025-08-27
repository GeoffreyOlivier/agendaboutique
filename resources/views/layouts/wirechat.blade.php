@extends('layouts.app')

@section('content')
<div class="wirechat-chats-layout">
    {{ $slot }}
</div>
@endsection
<style>
    /* Ajustements spécifiques pour Wirechat */
    
    /* Correction de la structure de la page des chats */
    .wirechat-page-container {
        display: flex !important;
        width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
    }
    
    /* Correction de la sidebar des conversations */
    .wirechat-conversations-sidebar {
        flex-shrink: 0 !important;
        width: 360px !important;
        border-right: 1px solid var(--wc-light-border) !important;
        overflow-y: auto !important;
        background: var(--wc-light-primary) !important;
    }
    
    .dark .wirechat-conversations-sidebar {
        border-right: 1px solid var(--wc-dark-border) !important;
        background: var(--wc-dark-primary) !important;
    }
    
    /* Correction de la zone principale du chat */
    .wirechat-main-chat-area {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        background: var(--wc-light-primary) !important;
    }
    
    .dark .wirechat-main-chat-area {
        background: var(--wc-dark-primary) !important;
    }
    
    /* Correction du footer sticky */
    footer {
        position: sticky !important;
        bottom: 0 !important;
        background: var(--wc-light-secondary) !important;
        z-index: 50 !important;
    }
    
    .dark footer {
        background: var(--wc-dark-secondary) !important;
    }
    
    /* Correction de la hauteur des messages */
    main[class*="overflow-y-auto"] {
        height: calc(100vh - 300px) !important;
        overflow-y: auto !important;
    }
    
    /* Correction de l'alignement des messages */
    .justify-end {
        justify-content: flex-end !important;
    }
    
    .justify-start {
        justify-content: flex-start !important;
    }
    
    /* Correction des variables CSS Wirechat */
    :root {
        --wc-light-primary: #ffffff;
        --wc-light-secondary: #f8fafc;
        --wc-light-border: #e2e8f0;
        --wc-dark-primary: #1e293b;
        --wc-dark-secondary: #334155;
        --wc-dark-border: #475569;
        --wc-brand-primary: #3b82f6;
    }
    
    .dark {
        --wc-light-primary: #1e293b;
        --wc-light-secondary: #334155;
        --wc-light-border: #475569;
        --wc-dark-primary: #0f172a;
        --wc-dark-secondary: #1e293b;
        --wc-dark-border: #334155;
    }
    
    /* Correction spécifique pour la page des chats */
    .wirechat-page-container > div:first-child {
        width: 360px !important;
        flex-shrink: 0 !important;
    }
    
    .wirechat-page-container > main {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
    }
</style>
@endpush
