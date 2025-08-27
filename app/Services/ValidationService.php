<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * Valider les URLs des réseaux sociaux
     */
    public function validateSocialUrls(array $data): array
    {
        $rules = [
            'instagram_url' => 'nullable|url|regex:/^https?:\/\/(www\.)?instagram\.com\//',
            'tiktok_url' => 'nullable|url|regex:/^https?:\/\/(www\.)?tiktok\.com\//',
            'facebook_url' => 'nullable|url|regex:/^https?:\/\/(www\.)?facebook\.com\//',
            'linkedin_url' => 'nullable|url|regex:/^https?:\/\/(www\.)?linkedin\.com\//',
            'site_web' => 'nullable|url',
        ];

        $validator = Validator::make($data, $rules, [
            'instagram_url.regex' => 'L\'URL Instagram doit être une URL Instagram valide.',
            'tiktok_url.regex' => 'L\'URL TikTok doit être une URL TikTok valide.',
            'facebook_url.regex' => 'L\'URL Facebook doit être une URL Facebook valide.',
            'linkedin_url.regex' => 'L\'URL LinkedIn doit être une URL LinkedIn valide.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Valider les coordonnées géographiques
     */
    public function validateGeographicData(array $data): array
    {
        $rules = [
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|regex:/^\d{5}$/',
            'pays' => 'required|string|max:100',
        ];

        $validator = Validator::make($data, $rules, [
            'code_postal.regex' => 'Le code postal doit contenir exactement 5 chiffres.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Valider les informations de contact
     */
    public function validateContactInfo(array $data): array
    {
        $rules = [
            'telephone' => 'nullable|string|regex:/^(\+33|0)[1-9](\d{8})$/',
            'email' => 'required|email|max:255',
        ];

        $validator = Validator::make($data, $rules, [
            'telephone.regex' => 'Le numéro de téléphone doit être un numéro français valide.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Valider les informations financières
     */
    public function validateFinancialInfo(array $data): array
    {
        $rules = [
            'siret' => 'nullable|string|regex:/^\d{14}$/',
            'tva' => 'nullable|string|regex:/^FR[A-Z0-9]{2}\d{9}$/',
            'loyer_depot_vente' => 'nullable|numeric|min:0',
            'loyer_permanence' => 'nullable|numeric|min:0',
            'commission_depot_vente' => 'nullable|numeric|min:0|max:100',
            'commission_permanence' => 'nullable|numeric|min:0|max:100',
        ];

        $validator = Validator::make($data, $rules, [
            'siret.regex' => 'Le SIRET doit contenir exactement 14 chiffres.',
            'tva.regex' => 'Le numéro de TVA doit être au format français valide.',
            'commission_depot_vente.max' => 'La commission ne peut pas dépasser 100%.',
            'commission_permanence.max' => 'La commission ne peut pas dépasser 100%.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Valider les informations d'craftsman
     */
    public function validateArtisanInfo(array $data): array
    {
        $rules = [
            'specialite' => 'required|string|max:100',
            'experience_annees' => 'nullable|integer|min:0|max:50',
            'tarif_horaire' => 'nullable|numeric|min:0',
            'tarif_jour' => 'nullable|numeric|min:0',
            'disponibilite' => 'required|in:disponible,partiellement_disponible,non_disponible',
        ];

        $validator = Validator::make($data, $rules, [
            'experience_annees.max' => 'L\'expérience ne peut pas dépasser 50 ans.',
            'disponibilite.in' => 'La disponibilité doit être l\'une des valeurs autorisées.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Valider les informations de produit
     */
    public function validateProductInfo(array $data): array
    {
        $rules = [
            'prix_base' => 'nullable|numeric|min:0',
            'prix_min' => 'nullable|numeric|min:0',
            'prix_max' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'duree_fabrication' => 'nullable|integer|min:1',
        ];

        $validator = Validator::make($data, $rules, [
            'prix_min.lte' => 'Le prix minimum ne peut pas être supérieur au prix maximum.',
            'prix_max.gte' => 'Le prix maximum ne peut pas être inférieur au prix minimum.',
            'stock.min' => 'Le stock ne peut pas être négatif.',
            'duree_fabrication.min' => 'La durée de fabrication doit être d\'au moins 1 jour.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Validation croisée des prix
        if (isset($data['prix_min']) && isset($data['prix_max']) && $data['prix_min'] > $data['prix_max']) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add('prix_min', 'Le prix minimum ne peut pas être supérieur au prix maximum.')
            );
        }

        return $data;
    }
}
