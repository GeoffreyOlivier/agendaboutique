<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // L'autorisation est gérée par le middleware 'resource.owner:produit'
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
            'description' => 'required|string|max:1000',
            'prix' => 'required|numeric|min:0',
            'categorie' => 'required|string|max:100',
            'materiaux' => 'nullable|array',
            'materiaux.*' => 'string|max:100',
            'dimensions' => 'nullable|array',
            'dimensions.largeur' => 'nullable|numeric|min:0',
            'dimensions.hauteur' => 'nullable|numeric|min:0',
            'dimensions.profondeur' => 'nullable|numeric|min:0',
            'couleur' => 'nullable|string|max:100',
            'instructions_entretien' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du produit est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'prix.required' => 'Le prix du produit est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'categorie.required' => 'La catégorie du produit est obligatoire.',
            'categorie.max' => 'La catégorie ne peut pas dépasser 100 caractères.',
            'materiaux.array' => 'Les matériaux doivent être une liste.',
            'materiaux.*.string' => 'Chaque matériau doit être une chaîne de caractères.',
            'materiaux.*.max' => 'Chaque matériau ne peut pas dépasser 100 caractères.',
            'dimensions.array' => 'Les dimensions doivent être une liste.',
            'dimensions.largeur.numeric' => 'La largeur doit être un nombre.',
            'dimensions.largeur.min' => 'La largeur ne peut pas être négative.',
            'dimensions.hauteur.numeric' => 'La hauteur doit être un nombre.',
            'dimensions.hauteur.min' => 'La hauteur ne peut pas être négative.',
            'dimensions.profondeur.numeric' => 'La profondeur doit être un nombre.',
            'dimensions.profondeur.min' => 'La profondeur ne peut pas être négative.',
            'couleur.max' => 'La couleur ne peut pas dépasser 100 caractères.',
            'instructions_entretien.max' => 'Les instructions d\'entretien ne peuvent pas dépasser 500 caractères.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Chaque image doit être au format JPEG, PNG, JPG ou GIF.',
            'images.*.max' => 'Chaque image ne peut pas dépasser 2 Mo.',
            'delete_images.array' => 'La liste des images à supprimer doit être une liste.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom du produit',
            'description' => 'description',
            'prix' => 'prix',
            'categorie' => 'catégorie',
            'materiaux' => 'matériaux',
            'dimensions' => 'dimensions',
            'dimensions.largeur' => 'largeur',
            'dimensions.hauteur' => 'hauteur',
            'dimensions.profondeur' => 'profondeur',
            'couleur' => 'couleur',
            'instructions_entretien' => 'instructions d\'entretien',
            'images' => 'images',
            'delete_images' => 'images à supprimer',
        ];
    }
}
