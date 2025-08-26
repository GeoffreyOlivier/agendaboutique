# Gestion des products - Fonctionnalités d'Édition et de Suppression

## Vue d'Édition (`/products/{id}/edit`)

### Fonctionnalités
- **Formulaire pré-rempli** : Toutes les informations du produit sont automatiquement chargées
- **Modification des champs** : Nom, description, prix, catégorie, matériaux, dimensions, couleur, instructions d'entretien
- **Gestion des images** : 
  - Affichage des images existantes avec possibilité de suppression
  - Ajout de nouvelles images
- **Validation** : Tous les champs obligatoires sont validés
- **Sécurité** : Seul l'artisan propriétaire du produit peut le modifier

### Utilisation
1. Cliquer sur le bouton "Modifier" dans la liste des products
2. Modifier les champs souhaités
3. Cliquer sur "Mettre à jour" pour sauvegarder
4. Ou cliquer sur "Annuler" pour revenir à la liste

## Suppression de Produit

### Fonctionnalités
- **Confirmation** : Demande de confirmation avant suppression
- **Suppression complète** : Le produit et ses images sont supprimés de la base de données et du stockage
- **Sécurité** : Seul l'artisan propriétaire du produit peut le supprimer
- **Redirection** : Retour automatique à la liste des products avec message de succès

### Utilisation
1. Cliquer sur le bouton "Supprimer" dans la liste des products
2. Confirmer la suppression dans la boîte de dialogue
3. Le produit est supprimé et vous êtes redirigé vers la liste

## Routes Utilisées

```php
// Édition
GET    /products/{id}/edit    → ProduitController@edit
PUT    /products/{id}         → ProduitController@update

// Suppression  
DELETE /products/{id}         → ProduitController@destroy
```

## Contrôleur

### Méthode `edit()`
- Vérifie que l'utilisateur connecté est l'artisan propriétaire du produit
- Retourne la vue d'édition avec les données du produit

### Méthode `update()`
- Valide les données soumises
- Met à jour le produit dans la base de données
- Gère la suppression et l'ajout d'images
- Redirige vers la liste des products avec un message de succès

### Méthode `destroy()`
- Vérifie les permissions
- Supprime les images du stockage
- Supprime le produit de la base de données
- Redirige vers la liste avec un message de succès

## Sécurité

- **Middleware d'authentification** : Seuls les utilisateurs connectés peuvent accéder
- **Vérification des rôles** : Seuls les artisans peuvent gérer leurs products
- **Vérification de propriété** : Un artisan ne peut modifier/supprimer que ses propres products
- **Validation des données** : Tous les champs sont validés côté serveur

## Messages d'Erreur et de Succès

- **Succès** : "Produit mis à jour avec succès !" / "Produit supprimé avec succès !"
- **Erreur d'accès** : "Accès non autorisé."
- **Erreur de validation** : Messages spécifiques pour chaque champ

## Interface Utilisateur

- **Design responsive** : S'adapte aux différentes tailles d'écran
- **Thème sombre/clair** : Support des deux modes
- **Boutons d'action** : Modifier (vert) et Supprimer (rouge)
- **Navigation** : Bouton retour vers la liste des products
- **Formulaires** : Validation en temps réel et messages d'erreur
