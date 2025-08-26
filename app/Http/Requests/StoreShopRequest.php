<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // L'autorisation est gérée par le middleware 'role:shop'
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'size' => 'required|in:small,medium,large',
            'siret' => 'nullable|string|max:14',
            'vat_number' => 'nullable|string|max:20',
            'deposit_sale_rent' => 'nullable|numeric|min:0',
            'permanent_rent' => 'nullable|numeric|min:0',
            'deposit_sale_commission' => 'nullable|numeric|min:0|max:100',
            'permanent_commission' => 'nullable|numeric|min:0|max:100',
            'monthly_permanences' => 'nullable|integer|min:0',
            'website' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'opening_hours' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la boutique est obligatoire.',
            'name.max' => 'Le nom de la boutique ne peut pas dépasser 255 caractères.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'postal_code.max' => 'Le code postal ne peut pas dépasser 10 caractères.',
            'country.required' => 'Le pays est obligatoire.',
            'size.required' => 'La taille de la boutique est obligatoire.',
            'size.in' => 'La taille doit être small, medium ou large.',
            'siret.max' => 'Le numéro SIRET ne peut pas dépasser 14 caractères.',
            'vat_number.max' => 'Le numéro de TVA ne peut pas dépasser 20 caractères.',
            'deposit_sale_rent.numeric' => 'Le loyer dépôt-vente doit être un nombre.',
            'deposit_sale_rent.min' => 'Le loyer dépôt-vente ne peut pas être négatif.',
            'permanent_rent.numeric' => 'Le loyer permanence doit être un nombre.',
            'permanent_rent.min' => 'Le loyer permanence ne peut pas être négatif.',
            'deposit_sale_commission.numeric' => 'La commission dépôt-vente doit être un nombre.',
            'deposit_sale_commission.min' => 'La commission dépôt-vente ne peut pas être négative.',
            'deposit_sale_commission.max' => 'La commission dépôt-vente ne peut pas dépasser 100%.',
            'permanent_commission.numeric' => 'La commission permanence doit être un nombre.',
            'permanent_commission.min' => 'La commission permanence ne peut pas être négative.',
            'permanent_commission.max' => 'La commission permanence ne peut pas dépasser 100%.',
            'monthly_permanences.integer' => 'Le nombre de permanences doit être un nombre entier.',
            'monthly_permanences.min' => 'Le nombre de permanences ne peut pas être négatif.',
            'website.url' => 'L\'URL du site web doit être valide.',
            'instagram_url.url' => 'L\'URL Instagram doit être valide.',
            'tiktok_url.url' => 'L\'URL TikTok doit être valide.',
            'facebook_url.url' => 'L\'URL Facebook doit être valide.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom de la boutique',
            'description' => 'description',
            'address' => 'adresse',
            'city' => 'ville',
            'postal_code' => 'code postal',
            'country' => 'pays',
            'phone' => 'téléphone',
            'email' => 'email',
            'size' => 'taille',
            'siret' => 'numéro SIRET',
            'vat_number' => 'numéro de TVA',
            'deposit_sale_rent' => 'loyer dépôt-vente',
            'permanent_rent' => 'loyer permanence',
            'deposit_sale_commission' => 'commission dépôt-vente',
            'permanent_commission' => 'commission permanence',
            'monthly_permanences' => 'nombre de permanences',
            'website' => 'site web',
            'instagram_url' => 'URL Instagram',
            'tiktok_url' => 'URL TikTok',
            'facebook_url' => 'URL Facebook',
            'opening_hours' => 'horaires d\'ouverture',
        ];
    }
}
