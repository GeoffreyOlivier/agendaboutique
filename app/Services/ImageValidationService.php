<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImageValidationService
{
    /**
     * Règles de validation par défaut pour les images
     */
    protected array $defaultRules = [
        'image',
        'mimes:jpeg,png,jpg,gif,webp',
        'max:2048', // 2MB
    ];

    /**
     * Messages d'erreur personnalisés
     */
    protected array $customMessages = [
        'image' => 'Le fichier sélectionné doit être une image valide.',
        'mimes' => 'L\'image doit être au format : :values.',
        'max' => 'L\'image ne peut pas dépasser :max Ko.',
        'dimensions' => 'L\'image doit respecter les dimensions suivantes : :width x :height pixels.',
    ];

    /**
     * Attributs personnalisés
     */
    protected array $customAttributes = [
        'image' => 'image',
    ];

    /**
     * Valider une image avec des règles personnalisées
     */
    public function validate(UploadedFile $file, array $rules = [], array $messages = [], array $attributes = []): array
    {
        $finalRules = array_merge($this->defaultRules, $rules);
        $finalMessages = array_merge($this->customMessages, $messages);
        $finalAttributes = array_merge($this->customAttributes, $attributes);

        $validator = Validator::make(
            ['image' => $file],
            ['image' => $finalRules],
            $finalMessages,
            $finalAttributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->getImageInfo($file);
    }

    /**
     * Valider plusieurs images
     */
    public function validateMultiple(array $files, array $rules = [], array $messages = [], array $attributes = []): array
    {
        $results = [];
        
        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                try {
                    $results[$index] = $this->validate($file, $rules, $messages, $attributes);
                } catch (ValidationException $e) {
                    $results[$index] = [
                        'error' => true,
                        'message' => $e->getMessage(),
                        'file' => $file->getClientOriginalName(),
                    ];
                }
            }
        }
        
        return $results;
    }

    /**
     * Obtenir les informations d'une image
     */
    protected function getImageInfo(UploadedFile $file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'size_formatted' => $this->formatFileSize($file->getSize()),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'dimensions' => $this->getImageDimensions($file),
        ];
    }

    /**
     * Obtenir les dimensions d'une image
     */
    protected function getImageDimensions(UploadedFile $file): ?array
    {
        try {
            $image = \Intervention\Image\Facades\Image::make($file);
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'aspect_ratio' => round($image->width() / $image->height(), 2),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Formater la taille du fichier
     */
    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Obtenir les recommandations de taille pour l'utilisateur
     */
    public function getSizeRecommendations(): array
    {
        return [
            'max_size' => '2 MB',
            'max_size_bytes' => 2 * 1024 * 1024,
            'recommended_formats' => ['JPEG', 'PNG', 'JPG', 'GIF', 'WebP'],
            'recommended_dimensions' => [
                'boutique' => '1200 x 800 pixels',
                'produit' => '1600 x 1200 pixels',
            ],
            'tips' => [
                'Utilisez des images au format JPEG pour les photos',
                'Utilisez des images au format PNG pour les images avec transparence',
                'Compressez vos images avant de les télécharger',
                'Redimensionnez vos images aux dimensions recommandées',
            ],
        ];
    }

    /**
     * Vérifier si une image respecte les recommandations
     */
    public function checkImageCompliance(UploadedFile $file): array
    {
        $info = $this->getImageInfo($file);
        $recommendations = $this->getSizeRecommendations();
        
        $issues = [];
        $warnings = [];
        
        // Vérifier la taille
        if ($info['size'] > $recommendations['max_size_bytes']) {
            $issues[] = "L'image est trop lourde ({$info['size_formatted']}). Taille maximale : {$recommendations['max_size']}";
        }
        
        // Vérifier le format
        if (!in_array(strtoupper($info['extension']), array_map('strtoupper', $recommendations['recommended_formats']))) {
            $warnings[] = "Format recommandé : " . implode(', ', $recommendations['recommended_formats']);
        }
        
        // Vérifier les dimensions
        if ($info['dimensions']) {
            $width = $info['dimensions']['width'];
            $height = $info['dimensions']['height'];
            
            if ($width < 800 || $height < 600) {
                $warnings[] = "L'image est de petite taille ({$width} x {$height} pixels). Recommandé : au moins 800 x 600 pixels";
            }
            
            if ($width > 3000 || $height > 3000) {
                $warnings[] = "L'image est très grande ({$width} x {$height} pixels). Cela peut ralentir le chargement";
            }
        }
        
        return [
            'compliant' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
            'info' => $info,
        ];
    }
}
