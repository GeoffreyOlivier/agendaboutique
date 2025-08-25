<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Craftsman extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'description',
        'specialty',
        'experience_years',
        'education',
        'certifications',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'instagram_url',
        'tiktok_url',
        'facebook_url',
        'linkedin_url',
        'portfolio_url',
        'hourly_rate',
        'daily_rate',
        'availability',
        'status',
        'active',
        'avatar',
        'rejection_reason',
    ];

    protected $casts = [
        'active' => 'boolean',
        'certifications' => 'array',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(CraftsmanRequest::class);
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_craftsman');
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

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->postal_code, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getSpecialtyListAttribute(): string
    {
        return $this->specialty ?? 'No specialty specified';
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

    public function getAvailabilityLabelAttribute(): string
    {
        return match($this->availability) {
            'available' => 'Available',
            'busy' => 'Busy',
            'unavailable' => 'Unavailable',
            default => 'Unknown'
        };
    }

    // Mutators
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
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
