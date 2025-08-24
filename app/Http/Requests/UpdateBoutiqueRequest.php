<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoutiqueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // L'autorisation est gérée par le middleware 'resource.owner:boutique'
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
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'taille' => 'required|in:petite,moyenne,grande',
            'siret' => 'nullable|string|max:14',
            'tva' => 'nullable|string|max:20',
            'loyer_depot_vente' => 'nullable|numeric|min:0',
            'loyer_permanence' => 'nullable|numeric|min:0',
            'commission_depot_vente' => 'nullable|numeric|min:0|max:100',
            'commission_permanence' => 'nullable|numeric|min:0|max:100',
            'nb_permanences_mois_indicatif' => 'nullable|integer|min:0',
            'site_web' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'horaires_ouverture' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la boutique est obligatoire.',
            'nom.max' => 'Le nom de la boutique ne peut pas dépasser 255 caractères.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'code_postal.required' => 'Le code postal est obligatoire.',
            'code_postal.max' => 'Le code postal ne peut pas dépasser 10 caractères.',
            'pays.required' => 'Le pays est obligatoire.',
            'taille.required' => 'La taille de la boutique est obligatoire.',
            'taille.in' => 'La taille doit être petite, moyenne ou grande.',
            'siret.max' => 'Le numéro SIRET ne peut pas dépasser 14 caractères.',
            'tva.max' => 'Le numéro de TVA ne peut pas dépasser 20 caractères.',
            'loyer_depot_vente.numeric' => 'Le loyer dépôt-vente doit être un nombre.',
            'loyer_depot_vente.min' => 'Le loyer dépôt-vente ne peut pas être négatif.',
            'loyer_permanence.numeric' => 'Le loyer permanence doit être un nombre.',
            'loyer_permanence.min' => 'Le loyer permanence ne peut pas être négatif.',
            'commission_depot_vente.numeric' => 'La commission dépôt-vente doit être un nombre.',
            'commission_depot_vente.min' => 'La commission dépôt-vente ne peut pas être négative.',
            'commission_depot_vente.max' => 'La commission dépôt-vente ne peut pas dépasser 100%.',
            'commission_permanence.numeric' => 'La commission permanence doit être un nombre.',
            'commission_permanence.min' => 'La commission permanence ne peut pas être négative.',
            'commission_permanence.max' => 'La commission permanence ne peut pas dépasser 100%.',
            'nb_permanences_mois_indicatif.integer' => 'Le nombre de permanences doit être un nombre entier.',
            'nb_permanences_mois_indicatif.min' => 'Le nombre de permanences ne peut pas être négatif.',
            'site_web.url' => 'L\'URL du site web doit être valide.',
            'instagram_url.url' => 'L\'URL Instagram doit être valide.',
            'tiktok_url.url' => 'L\'URL TikTok doit être valide.',
            'facebook_url.url' => 'L\'URL Facebook doit être valide.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
            'photo.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom de la boutique',
            'description' => 'description',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'code_postal' => 'code postal',
            'pays' => 'pays',
            'telephone' => 'téléphone',
            'email' => 'email',
            'taille' => 'taille',
            'siret' => 'numéro SIRET',
            'tva' => 'numéro de TVA',
            'loyer_depot_vente' => 'loyer dépôt-vente',
            'loyer_permanence' => 'loyer permanence',
            'commission_depot_vente' => 'commission dépôt-vente',
            'commission_permanence' => 'commission permanence',
            'nb_permanences_mois_indicatif' => 'nombre de permanences',
            'site_web' => 'site web',
            'instagram_url' => 'URL Instagram',
            'tiktok_url' => 'URL TikTok',
            'facebook_url' => 'URL Facebook',
            'horaires_ouverture' => 'horaires d\'ouverture',
            'photo' => 'photo',
        ];
    }
}
