# Fonctionnalité Chat - Contact avec les Artisans

## Vue d'ensemble

Cette fonctionnalité permet aux utilisateurs ayant le rôle "boutique" de contacter directement les artisans via un système de chat intégré. Quand un utilisateur boutique clique sur le bouton "Contacter l'craftsman", il est redirigé vers la page de chat avec une nouvelle conversation ou une conversation existante.

## Fonctionnalités implémentées

### ✅ Contrôleur ChatController
- **Fichier** : `app/Http/Controllers/ChatController.php`
- **Méthode principale** : `startConversationWithArtisan($artisanId)`
- **Fonctionnalités** :
  - Vérification des permissions (rôle boutique requis)
  - Vérification du statut de l'craftsman (doit être approuvé)
  - Création automatique de conversation via WireChat
  - Redirection vers la page de chat

### ✅ Route ajoutée
- **URL** : `/chat/craftsman/{craftsman}`
- **Nom** : `chat.craftsman.start`
- **Méthode** : GET
- **Middleware** : `auth`
- **Contrôleur** : `ChatController@startConversationWithArtisan`

### ✅ Interface utilisateur mise à jour
- **Profil de l'craftsman** : Bouton "Contacter l'craftsman" transformé en lien fonctionnel
- **Liste des products** : Bouton "Contacter" redirige vers le profil de l'craftsman
- **Navigation** : Redirection automatique vers la page de chat

### ✅ Intégration WireChat
- Utilisation du trait `Chatable` du modèle User
- Création automatique de conversations privées
- Gestion des participants et des rôles
- Support des conversations existantes

## Comment ça fonctionne

### 1. Flux utilisateur
1. L'utilisateur boutique va sur la page des artisans (`/shop/artisans`)
2. Il clique sur "Voir le profil" d'un craftsman
3. Sur le profil de l'craftsman, il clique sur "Contacter l'craftsman"
4. Il est automatiquement redirigé vers la page de chat avec la conversation ouverte

### 2. Vérifications de sécurité
- ✅ L'utilisateur doit avoir le rôle "boutique"
- ✅ L'craftsman doit avoir le statut "approuve"
- ✅ L'craftsman doit avoir un utilisateur associé
- ✅ L'utilisateur ne peut pas se contacter lui-même

### 3. Gestion des conversations
- **Nouvelle conversation** : Créée automatiquement si aucune n'existe
- **Conversation existante** : Récupérée si elle existe déjà
- **Participants** : L'utilisateur boutique et l'craftsman sont ajoutés automatiquement
- **Type** : Conversation privée (1-1)
- **Redirection** : Redirection directe vers la conversation spécifique (`/chats/{id}`)

## Fichiers modifiés

### Nouveaux fichiers
- `app/Http/Controllers/ChatController.php` - Contrôleur principal
- `resources/views/test-chat.blade.php` - Page de test (optionnelle)
- `FONCTIONNALITE_CHAT.md` - Cette documentation

### Fichiers modifiés
- `routes/web.php` - Ajout de la route de chat
- `resources/views/interfaces/shop/craftsman-profile.blade.php` - Bouton "Contacter l'craftsman"
- `resources/views/products/index-public.blade.php` - Bouton "Contacter"

## Tests effectués

### ✅ Test de création de conversation
- Vérification des utilisateurs boutique
- Vérification des artisans approuvés
- Test de création de conversation
- Vérification des participants
- Vérification des rôles

### ✅ Test des routes
- Route `/chat/craftsman/{craftsman}` accessible
- Redirection vers `/chats/{id}` fonctionnelle
- Intégration avec WireChat

## Utilisation

### Pour les utilisateurs boutique
1. Se connecter avec un compte ayant le rôle "boutique"
2. Aller sur `/shop/artisans`
3. Cliquer sur "Voir le profil" d'un craftsman
4. Cliquer sur "Contacter l'craftsman"
5. Être redirigé vers la page de chat

### Pour les développeurs
```php
// Créer une conversation avec un craftsman
$conversation = $user->createConversationWith($craftsman->user);

// Rediriger vers la conversation spécifique
return redirect()->route('chat', $conversation->id);
```

## Dépendances

- **Laravel** : Framework principal
- **WireChat** : Package de chat (namu/wirechat)
- **Spatie Laravel Permission** : Gestion des rôles
- **Livewire** : Composants dynamiques

## Configuration requise

- Base de données avec les tables WireChat
- Utilisateurs avec rôles "boutique" et "craftsman"
- Artisans avec statut "approuve"
- Middleware d'authentification actif

## Dépannage

### Erreurs courantes
1. **"Vous devez avoir un rôle boutique"** : L'utilisateur n'a pas le bon rôle
2. **"Cet craftsman n'est pas encore approuvé"** : L'craftsman n'est pas approuvé
3. **"Impossible de contacter cet craftsman"** : L'craftsman n'a pas d'utilisateur associé

### Vérifications
- Vérifier que l'utilisateur a le rôle "shop"
- Vérifier que l'craftsman a le statut "approuve"
- Vérifier que l'craftsman a un utilisateur associé
- Vérifier que les tables WireChat existent

## Évolutions futures

- Ajout de notifications push
- Support des groupes de chat
- Historique des conversations
- Statuts de lecture des messages
- Support des fichiers et images
- Intégration avec les demandes d'craftsman

## Support

Pour toute question ou problème avec cette fonctionnalité, consulter :
- La documentation WireChat
- Les logs Laravel
- Les tests de fonctionnalité
- Cette documentation
