<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'size',
        'siret',
        'vat_number',
        'deposit_sale_rent',
        'permanent_rent',
        'deposit_sale_commission',
        'permanent_commission',
        'monthly_permanences',
        'website',
        'instagram_url',
        'tiktok_url',
        'facebook_url',
        'opening_hours',
        'photo',
        'status',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'deposit_sale_rent' => 'decimal:2',
        'permanent_rent' => 'decimal:2',
        'deposit_sale_commission' => 'decimal:2',
        'permanent_commission' => 'decimal:2',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(CraftsmanRequest::class);
    }

    public function craftsmen(): BelongsToMany
    {
        return $this->belongsToMany(Craftsman::class, 'shop_craftsman')
                    ->withPivot(['status', 'shop_comment', 'craftsman_comment', 'start_date', 'end_date', 'commission_percentage', 'permanent_exhibition', 'temporary_exhibition'])
                    ->withTimestamps();
    }

    public function shopCraftsmen(): HasMany
    {
        return $this->hasMany(ShopCraftsman::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->postal_code, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    public function getExhibitorsCountAttribute(): string
    {
        return match($this->size) {
            'small' => '1-5 exhibitors',
            'medium' => '6-15 exhibitors',
            'large' => '16+ exhibitors',
            default => 'Not defined'
        };
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown'
        };
    }

    public function getSizeLabelAttribute(): string
    {
        return match($this->size) {
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            default => 'Not defined'
        };
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = ucfirst(strtolower($value));
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = ucfirst(strtolower($value));
    }
}
