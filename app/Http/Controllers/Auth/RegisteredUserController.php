<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:shop,craftsman'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Créer le rôle choisi s'il n'existe pas et l'assigner à l'utilisateur
        $role = Role::firstOrCreate(['name' => $request->role], ['guard_name' => 'web']);
        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user);

        // Rediriger vers le dashboard approprié selon le rôle choisi
        if ($request->role === 'shop') {
            return redirect()->route('dashboard')->with('success', 'Compte créé avec succès ! Vous avez le rôle de boutique.');
        } else {
            return redirect()->route('dashboard')->with('success', 'Compte créé avec succès ! Vous avez le rôle d\'artisan.');
        }
    }
}
