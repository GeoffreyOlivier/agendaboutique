<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Artisan;
use App\Models\Craftsman;

class ChatController extends Controller
{
    /**
     * Créer ou récupérer une conversation avec un artisan
     */
    public function startConversationWithArtisan($artisanId)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a le rôle boutique
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un rôle boutique pour contacter des artisans.');
        }
        
        // Récupérer l'artisan
        $artisan = Artisan::with('user')->findOrFail($artisanId);
        
        // Vérifier que l'artisan est approuvé
        if ($artisan->statut !== 'approuve') {
            return redirect()->back()->with('error', 'Cet artisan n\'est pas encore approuvé.');
        }
        
        // Récupérer l'utilisateur de l'artisan
        $artisanUser = $artisan->user;
        
        // Vérifier que l'artisan a un utilisateur associé
        if (!$artisanUser) {
            return redirect()->back()->with('error', 'Impossible de contacter cet artisan.');
        }
        
        // Vérifier que l'utilisateur ne tente pas de se contacter lui-même
        if ($user->id === $artisanUser->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
        }
        
        try {
            // Utiliser la méthode du trait Chatable pour créer ou récupérer la conversation
            $conversation = $user->createConversationWith($artisanUser);
            
            // Rediriger vers la conversation spécifique
            return redirect()->route('chat', $conversation->id);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la conversation : ' . $e->getMessage());
        }
    }

    /**
     * Créer ou récupérer une conversation avec un craftsman (alias pour artisan)
     */
    public function startConversationWithCraftsman($craftsmanId)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a le rôle boutique
        if (!$user->isShop()) {
            return redirect()->route('dashboard')->with('error', 'Vous devez avoir un rôle boutique pour contacter des artisans.');
        }
        
        // Récupérer le craftsman
        $craftsman = Craftsman::with('user')->findOrFail($craftsmanId);
        
        // Vérifier que le craftsman est approuvé
        if ($craftsman->status !== 'approved') {
            return redirect()->back()->with('error', 'Cet artisan n\'est pas encore approuvé.');
        }
        
        // Récupérer l'utilisateur du craftsman
        $craftsmanUser = $craftsman->user;
        
        // Vérifier que le craftsman a un utilisateur associé
        if (!$craftsmanUser) {
            return redirect()->back()->with('error', 'Impossible de contacter cet artisan.');
        }
        
        // Vérifier que l'utilisateur ne tente pas de se contacter lui-même
        if ($user->id === $craftsmanUser->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
        }
        
        try {
            // Utiliser la méthode du trait Chatable pour créer ou récupérer la conversation
            $conversation = $user->createConversationWith($craftsmanUser);
            
            // Rediriger vers la conversation spécifique
            return redirect()->route('chat', $conversation->id);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la conversation : ' . $e->getMessage());
        }
    }

}
