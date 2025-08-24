<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour le stockage et l'optimisation des images
    |
    */

    'default_disk' => env('IMAGE_STORAGE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Default Optimization Options
    |--------------------------------------------------------------------------
    |
    | Options par défaut pour l'optimisation des images
    |
    */
    'default_options' => [
        'max_width' => 1920,
        'max_height' => 1080,
        'quality' => 85,
        'format' => 'jpg',
        'optimize' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Boutique Images Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration spécifique pour les images de boutiques
    |
    */
    'boutique' => [
        'path' => 'boutiques/photos',
        'max_width' => 1200,
        'max_height' => 800,
        'quality' => 85,
        'optimize' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Produit Images Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration spécifique pour les images de produits
    |
    */
    'produit' => [
        'path' => 'produits',
        'max_width' => 1600,
        'max_height' => 1200,
        'quality' => 90,
        'optimize' => true,
        'thumbnails' => [
            'width' => 300,
            'height' => 300,
            'quality' => 80,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    |
    | Types de fichiers autorisés pour les images
    |
    */
    'allowed_types' => [
        'jpeg', 'jpg', 'png', 'gif', 'webp'
    ],

    /*
    |--------------------------------------------------------------------------
    | Max File Size (in bytes)
    |--------------------------------------------------------------------------
    |
    | Taille maximale des fichiers (2MB par défaut)
    |
    */
    'max_file_size' => 2 * 1024 * 1024, // 2MB

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Generation
    |--------------------------------------------------------------------------
    |
    | Configuration pour la génération des vignettes
    |
    */
    'thumbnails' => [
        'enabled' => true,
        'sizes' => [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ],
    ],
];
