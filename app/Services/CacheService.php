<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    protected int $defaultTtl = 3600; // 1 heure par défaut

    /**
     * Obtenir une valeur du cache ou la calculer
     */
    public function remember(string $key, callable $callback, int $ttl = null): mixed
    {
        $ttl = $ttl ?? $this->defaultTtl;
        
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning("Erreur de cache pour la clé '{$key}': " . $e->getMessage());
            return $callback();
        }
    }

    /**
     * Obtenir une valeur du cache
     */
    public function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::warning("Erreur de lecture du cache pour la clé '{$key}': " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Stocker une valeur dans le cache
     */
    public function put(string $key, mixed $value, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::warning("Erreur d'écriture du cache pour la clé '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer une valeur du cache
     */
    public function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::warning("Erreur de suppression du cache pour la clé '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer plusieurs clés du cache
     */
    public function forgetMultiple(array $keys): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->forget($key);
        }
        return $results;
    }

    /**
     * Vérifier si une clé existe dans le cache
     */
    public function has(string $key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Exception $e) {
            Log::warning("Erreur de vérification du cache pour la clé '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir ou stocker une valeur avec TTL personnalisé
     */
    public function rememberWithCustomTtl(string $key, callable $callback, int $ttl): mixed
    {
        return $this->remember($key, $callback, $ttl);
    }

    /**
     * Incrémenter une valeur dans le cache
     */
    public function increment(string $key, int $value = 1): int
    {
        try {
            return Cache::increment($key, $value);
        } catch (\Exception $e) {
            Log::warning("Erreur d'incrémentation du cache pour la clé '{$key}': " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Décrémenter une valeur dans le cache
     */
    public function decrement(string $key, int $value = 1): int
    {
        try {
            return Cache::decrement($key, $value);
        } catch (\Exception $e) {
            Log::warning("Erreur de décrémentation du cache pour la clé '{$key}': " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Nettoyer le cache par tags
     */
    public function flushTags(array $tags): bool
    {
        try {
            return Cache::tags($tags)->flush();
        } catch (\Exception $e) {
            Log::warning("Erreur de nettoyage du cache par tags: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir des statistiques du cache
     */
    public function getStats(): array
    {
        try {
            return [
                'driver' => config('cache.default'),
                'prefix' => config('cache.prefix'),
                'ttl_default' => $this->defaultTtl,
            ];
        } catch (\Exception $e) {
            Log::warning("Erreur lors de la récupération des stats du cache: " . $e->getMessage());
            return [];
        }
    }
}
