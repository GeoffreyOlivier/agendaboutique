<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // L'autorisation est gérée par le middleware 'role:craftsman'
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
            'description' => 'required|string|max:1000',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'material' => 'nullable|string|max:500',
            'dimensions' => 'nullable|array',
            'dimensions.width' => 'nullable|numeric|min:0',
            'dimensions.height' => 'nullable|numeric|min:0',
            'dimensions.depth' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string|max:500',
            'care_instructions' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du produit est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'base_price.required' => 'Le prix du produit est obligatoire.',
            'base_price.numeric' => 'Le prix doit être un nombre.',
            'base_price.min' => 'Le prix ne peut pas être négatif.',
            'category.required' => 'La catégorie du produit est obligatoire.',
            'category.max' => 'La catégorie ne peut pas dépasser 100 caractères.',
            'material.max' => 'Les matériaux ne peuvent pas dépasser 500 caractères.',
            'dimensions.array' => 'Les dimensions doivent être une liste.',
            'dimensions.width.numeric' => 'La largeur doit être un nombre.',
            'dimensions.width.min' => 'La largeur ne peut pas être négative.',
            'dimensions.height.numeric' => 'La hauteur doit être un nombre.',
            'dimensions.height.min' => 'La hauteur ne peut pas être négative.',
            'dimensions.depth.numeric' => 'La profondeur doit être un nombre.',
            'dimensions.depth.min' => 'La profondeur ne peut pas être négative.',
            'tags.max' => 'Les tags ne peuvent pas dépasser 500 caractères.',
            'care_instructions.max' => 'Les instructions d\'entretien ne peuvent pas dépasser 500 caractères.',
            'images.*.image' => 'Chaque fichier doit être une image valide.',
            'images.*.mimes' => 'Chaque image doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'images.*.max' => 'Chaque image ne peut pas dépasser 2 Mo. Veuillez compresser vos images ou choisir des fichiers plus légers.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom du produit',
            'description' => 'description',
            'base_price' => 'prix',
            'category' => 'catégorie',
            'material' => 'matériaux',
            'dimensions' => 'dimensions',
            'dimensions.width' => 'largeur',
            'dimensions.height' => 'hauteur',
            'dimensions.depth' => 'profondeur',
            'tags' => 'tags',
            'care_instructions' => 'instructions d\'entretien',
            'images' => 'images',
        ];
    }
}
