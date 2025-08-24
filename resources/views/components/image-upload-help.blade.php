@props(['type' => 'general'])

@php
    $recommendations = [
        'general' => [
            'max_size' => '2 MB',
            'formats' => ['JPEG', 'PNG', 'JPG', 'GIF', 'WebP'],
            'dimensions' => 'Minimum 800 x 600 pixels',
        ],
        'boutique' => [
            'max_size' => '2 MB',
            'formats' => ['JPEG', 'PNG', 'JPG', 'GIF', 'WebP'],
            'dimensions' => 'Recommandé : 1200 x 800 pixels',
            'tips' => [
                'Utilisez des images de haute qualité pour représenter votre boutique',
                'Évitez les images trop sombres ou floues',
                'Privilégiez un format paysage pour une meilleure présentation',
            ],
        ],
        'produit' => [
            'max_size' => '2 MB',
            'formats' => ['JPEG', 'PNG', 'JPG', 'GIF', 'WebP'],
            'dimensions' => 'Recommandé : 1600 x 1200 pixels',
            'tips' => [
                'Prenez vos photos sous un bon éclairage',
                'Montrez le produit sous différents angles',
                'Utilisez un arrière-plan neutre pour mettre en valeur le produit',
            ],
        ],
    ];

    $current = $recommendations[$type] ?? $recommendations['general'];
@endphp

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">
                Recommandations pour vos images
            </h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Taille maximale :</strong> {{ $current['max_size'] }}</li>
                    <li><strong>Formats acceptés :</strong> {{ implode(', ', $current['formats']) }}</li>
                    <li><strong>Dimensions :</strong> {{ $current['dimensions'] }}</li>
                </ul>
                
                @if(isset($current['tips']))
                    <div class="mt-3">
                        <p class="font-medium text-blue-800 mb-2">Conseils :</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach($current['tips'] as $tip)
                                <li>{{ $tip }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="mt-3 p-2 bg-blue-100 rounded text-xs">
                    <p class="font-medium text-blue-800">💡 Astuce :</p>
                    <p class="text-blue-700">Si votre image est trop lourde, utilisez un outil en ligne comme TinyPNG ou Compressor.io pour la compresser sans perte de qualité visible.</p>
                </div>
            </div>
        </div>
    </div>
</div>
