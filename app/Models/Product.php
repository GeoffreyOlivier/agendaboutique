<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'craftsman_id',
        'name',
        'description',
        'base_price',
        'min_price',
        'max_price',
        'price_hidden',
        'category',
        'tags',
        'images',
        'main_image',
        'material',
        'dimensions',
        'care_instructions',
        'status',
        'available',
        'stock',
        'reference',
        'production_time',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'price_hidden' => 'boolean',
        'tags' => 'array',
        'dimensions' => 'array',
        'images' => 'array',
        'available' => 'boolean',
    ];

    // Relations
    public function craftsman(): BelongsTo
    {
        return $this->belongsTo(Craftsman::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        if ($this->base_price) {
            return number_format($this->base_price, 2, '.', ',') . ' €';
        }
        if ($this->min_price && $this->max_price) {
            return number_format($this->min_price, 2, '.', ',') . ' - ' . number_format($this->max_price, 2, '.', ',') . ' €';
        }
        return 'Price on request';
    }

    public function getMainImageAttribute(): ?string
    {
        // If main_image is defined in database
        if ($this->attributes['main_image'] ?? null) {
            return $this->attributes['main_image'];
        }
        
        // Otherwise, take the first image from images array
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        
        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            default => 'Unknown'
        };
    }

    public function getAvailabilityLabelAttribute(): string
    {
        if ($this->available) {
            return $this->stock > 0 ? 'In Stock' : 'Out of Stock';
        }
        return 'Unavailable';
    }

    // Alias for compatibility with old code
    public function getPriceAttribute()
    {
        return $this->base_price;
    }

    public function setPriceAttribute($value)
    {
        $this->base_price = $value;
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = ucfirst(strtolower($value));
    }

    public function setMaterialAttribute($value)
    {
        $this->attributes['material'] = $value ? ucfirst(strtolower($value)) : null;
    }
}
