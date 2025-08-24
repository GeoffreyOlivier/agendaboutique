# Configuration des Uploads d'Images

## 🚨 Problème : Erreur 413 Request Entity Too Large

Cette erreur se produit quand le serveur web (Apache/Nginx) bloque la requête avant qu'elle n'atteigne Laravel, généralement à cause d'une image trop lourde.

## 🔧 Solutions à implémenter

### 1. Configuration PHP (php.ini)

Localisez votre fichier `php.ini` et modifiez ces valeurs :

```ini
; Taille maximale des données POST (doit être >= upload_max_filesize)
post_max_size = 10M

; Taille maximale d'un fichier uploadé
upload_max_filesize = 2M

; Nombre maximum de fichiers uploadés simultanément
max_file_uploads = 20

; Taille maximale de la mémoire pour traiter les uploads
memory_limit = 256M

; Temps maximum d'exécution pour les uploads
max_execution_time = 300

; Temps maximum d'input pour les uploads
max_input_time = 300
```

**Important :** `post_max_size` doit toujours être supérieur ou égal à `upload_max_filesize`.

### 2. Configuration Apache (.htaccess)

Le fichier `.htaccess` dans `public/` contient déjà la configuration nécessaire :

```apache
# Configuration pour les uploads de fichiers
<IfModule mod_php.c>
    php_value upload_max_filesize 2M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
    php_value memory_limit 256M
</IfModule>
```

### 3. Configuration Nginx

Si vous utilisez Nginx, ajoutez dans votre configuration de site :

```nginx
server {
    # Configuration pour les uploads
    client_max_body_size 10M;
    
    # Configuration PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeouts pour les uploads
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }
}
```

### 4. Redémarrer les services

Après modification des configurations :

```bash
# Apache
sudo systemctl restart apache2

# Nginx
sudo systemctl restart nginx

# PHP-FPM
sudo systemctl restart php8.1-fpm

# Ou redémarrer le serveur complet
sudo systemctl restart apache2 nginx php8.1-fpm
```

## 🧪 Tester la configuration

### Vérifier les limites PHP

Créez un fichier `phpinfo.php` temporaire :

```php
<?php phpinfo(); ?>
```

Recherchez ces valeurs :
- `upload_max_filesize`
- `post_max_size`
- `max_execution_time`
- `memory_limit`

### Tester avec une image

1. Essayez d'uploader une image de 1.5 MB
2. Essayez d'uploader une image de 2.5 MB (doit échouer avec un message d'erreur Laravel, pas 413)

## 📱 Validation côté client

Le fichier `resources/js/image-upload-validation.js` valide les images avant l'envoi :

- Vérifie la taille (max 2 MB)
- Vérifie le format (JPEG, PNG, JPG, GIF, WebP)
- Vérifie les dimensions
- Affiche des messages d'erreur clairs

## 🎯 Limites recommandées

- **Taille maximale** : 2 MB par image
- **Formats acceptés** : JPEG, PNG, JPG, GIF, WebP
- **Dimensions minimales** : 800 x 600 pixels
- **Dimensions recommandées** :
  - Boutiques : 1200 x 800 pixels
  - Produits : 1600 x 1200 pixels

## 🚀 Optimisation des images

Recommandations pour les utilisateurs :

1. **Compression** : Utiliser TinyPNG, Compressor.io
2. **Redimensionnement** : Resize avant upload
3. **Formats** : JPEG pour photos, PNG pour transparence
4. **Qualité** : 85-90% pour un bon compromis

## 🔍 Dépannage

### Erreur 413 persiste

1. Vérifiez que `.htaccess` est lu par Apache
2. Vérifiez que `mod_php` est activé
3. Vérifiez les logs Apache/Nginx
4. Redémarrez tous les services

### Erreur 413 remplacée par erreur Laravel

✅ **C'est bon !** L'erreur 413 est résolue, Laravel reçoit maintenant la requête et peut afficher ses propres messages d'erreur.

### Problèmes de permissions

```bash
# Vérifier les permissions du dossier storage
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```
