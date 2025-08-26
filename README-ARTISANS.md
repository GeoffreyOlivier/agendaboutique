# Gestion des Artisans - Agenda Boutique

## Vue d'ensemble

Cette fonctionnalité permet aux boutiques de visualiser et gérer tous les artisans associés à leur établissement. Elle offre une interface moderne et intuitive pour découvrir les talents et créateurs qui font vivre la boutique.

## Fonctionnalités

### 📊 Tableau de bord des artisans
- **Statistiques en temps réel** : Nombre total d'artisans, products disponibles, catégories représentées
- **Vue d'ensemble** : Informations clés sur chaque artisan associé

### 🔍 Recherche et filtrage
- **Recherche textuelle** : Par nom, description ou spécialités
- **Filtrage par catégorie** : Affichage des artisans par type de products
- **Interface responsive** : Optimisée pour tous les appareils

### 👤 Profils d'artisans
- **Photo de profil** : Image par défaut stylisée (SVG personnalisé)
- **Informations détaillées** : Nom, description, spécialités, expérience
- **Catégories de products** : Affichage des types de créations
- **Statistiques** : Nombre de products, années d'expérience

### 🎨 Interface utilisateur
- **Design moderne** : Utilisation de Tailwind CSS
- **Animations fluides** : Transitions et effets visuels
- **Responsive design** : Adaptation automatique aux écrans
- **Thème cohérent** : Intégration parfaite avec l'interface existante

## Accès

### Route
```
/shop/artisans
```

### Navigation
- **Tableau de bord boutique** → Section "Artisans associés" → "Voir tous les artisans"
- **Actions rapides** → "Voir tous les artisans"

## Structure technique

### Contrôleur
- `InterfaceController@shopArtisans` : Gestion de la logique métier

### Vue
- `resources/views/interfaces/shop/artisans.blade.php` : Interface utilisateur

### Styles
- `resources/themes/anchor/assets/css/artisans.css` : CSS personnalisé
- Intégration avec Vite pour la compilation des assets

### Modèles utilisés
- `Boutique` : Boutique de l'utilisateur connecté
- `BoutiqueArtisan` : Relation entre boutique et artisans
- `Artisan` : Informations des artisans
- `Produit` : products des artisans
- `Category` : Catégories de products

## Fonctionnalités futures

### Phase 2
- [ ] Gestion des demandes d'artisans
- [ ] Système de notation et avis
- [ ] Historique des collaborations
- [ ] Statistiques de performance

### Phase 3
- [ ] Messagerie interne
- [ ] Calendrier des expositions
- [ ] Gestion des commissions
- [ ] Rapports et analytics

## Maintenance

### Compilation des assets
```bash
npm run build
```

### Mise à jour des styles
Les modifications CSS doivent être faites dans `resources/themes/anchor/assets/css/artisans.css` puis recompilées avec Vite.

### Images par défaut
L'avatar par défaut des artisans se trouve dans `public/images/default-artisan-avatar.svg`.

## Support

Pour toute question ou suggestion concernant cette fonctionnalité, veuillez consulter la documentation technique ou contacter l'équipe de développement.
