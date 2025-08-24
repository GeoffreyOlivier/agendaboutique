<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResourceOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resourceType): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $resource = $request->route()->parameter($resourceType);

        if (!$resource) {
            abort(404, 'Ressource non trouvée.');
        }

        // Vérifier la propriété selon le type de ressource
        switch ($resourceType) {
            case 'boutique':
                if ($resource->user_id !== $user->id) {
                    return redirect()->route('dashboard')->with('error', 'Vous ne pouvez pas modifier cette boutique.');
                }
                break;
            case 'produit':
                if ($resource->artisan_id !== $user->artisan->id) {
                    return redirect()->route('dashboard')->with('error', 'Vous ne pouvez pas modifier ce produit.');
                }
                break;
            case 'artisan':
                if ($resource->user_id !== $user->id) {
                    return redirect()->route('dashboard')->with('error', 'Vous ne pouvez pas modifier ce profil artisan.');
                }
                break;
            default:
                abort(403, 'Type de ressource non supporté.');
        }

        return $next($request);
    }
}
