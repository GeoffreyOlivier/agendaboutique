<?php

namespace App\Models;

use Illuminate\Support\Str;
use Wave\User as WaveUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Wave\Traits\HasProfileKeyValues;
use Namu\WireChat\Traits\Chatable;

class User extends WaveUser
{
    use Notifiable, HasProfileKeyValues, HasFactory;
    use Chatable;

    public $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Listen for the creating event of the model
        static::creating(function ($user) {
            // Check if the username attribute is empty
            if (empty($user->username)) {
                // Use the name to generate a slugified username
                $username = Str::slug($user->name, '');
                $i = 1;
                while (self::where('username', $username)->exists()) {
                    $username = Str::slug($user->name, '') . $i;
                    $i++;
                }
                $user->username = $username;
            }
        });

        // Listen for the created event of the model
        static::created(function ($user) {
            // Remove all roles
            $user->syncRoles([]);
            // Assign the default role - Désactivé car géré dans le contrôleur d'inscription
            // $user->assignRole( config('wave.default_user_role', 'registered') );
        });
    }

    // Relations
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function craftsman()
    {
        return $this->hasOne(Craftsman::class);
    }

    // Méthodes pour vérifier les rôles
    public function isShop()
    {
        return $this->hasRole('shop');
    }

    public function isArtisan()
    {
        return $this->hasRole('craftsman');
    }

    public function isShopAndArtisan()
    {
        return $this->hasRole('shop') && $this->hasRole('craftsman');
    }

    public function isShopOnly()
    {
        return $this->hasRole('shop') && !$this->hasRole('craftsman');
    }

    public function isArtisanOnly()
    {
        return $this->hasRole('craftsman') && !$this->hasRole('shop');
    }

    // Méthodes pour assigner les rôles
    public function assignShopRole()
    {
        return $this->assignRole('shop');
    }

    public function assignArtisanRole()
    {
        return $this->assignRole('craftsman');
    }

    public function assignShopAndArtisanRoles()
    {
        return $this->syncRoles(['shop', 'craftsman']);
    }

    public function assignCombinedRole()
    {
        return $this->assignRole('shop-craftsman');
    }

    public function isShopArtisan()
    {
        return $this->hasRole('shop-craftsman');
    }

    // Méthodes pour récupérer les données associées
    public function getShopData()
    {
        return $this->shop;
    }

    public function getCraftsmanData()
    {
        return $this->craftsman;
    }

    // Méthode pour vérifier si l'utilisateur a des données complètes
    public function hasCompleteShopProfile()
    {
        return $this->isShop() && $this->shop && $this->shop->status === 'approved';
    }

    public function hasCompleteCraftsmanProfile()
    {
        return $this->isArtisan() && $this->craftsman && $this->craftsman->status === 'approved';
    }

    // Aliases pour maintenir la compatibilité temporaire
    public function getBoutiqueData()
    {
        return $this->getShopData();
    }

    public function getArtisanData()
    {
        return $this->getCraftsmanData();
    }

    public function hasCompleteArtisanProfile()
    {
        return $this->hasCompleteCraftsmanProfile();
    }
}
