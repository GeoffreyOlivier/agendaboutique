<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopCraftsman extends Model
{
    use HasFactory;

    protected $table = 'shop_craftsman';

    protected $fillable = [
        'shop_id',
        'craftsman_id',
        'status',
        'shop_comment',
        'craftsman_comment',
        'start_date',
        'end_date',
        'commission_percentage',
        'permanent_exhibition',
        'temporary_exhibition',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'commission_percentage' => 'decimal:2',
        'permanent_exhibition' => 'boolean',
        'temporary_exhibition' => 'boolean',
    ];

    // Relations
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function craftsman(): BelongsTo
    {
        return $this->belongsTo(Craftsman::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePermanentExhibitions($query)
    {
        return $query->where('permanent_exhibition', true);
    }

    public function scopeTemporaryExhibitions($query)
    {
        return $query->where('temporary_exhibition', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where(function($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })->where(function($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    // Accessors
    public function getFormattedCommissionAttribute(): string
    {
        return $this->commission_percentage . '%';
    }

    public function getIsActiveAttribute(): bool
    {
        if (!$this->start_date) return true;
        if (!$this->end_date) return $this->start_date <= now();
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'rejected' => 'Rejected',
            default => $this->status
        };
    }

    public function getExhibitionTypeAttribute(): string
    {
        if ($this->permanent_exhibition && $this->temporary_exhibition) {
            return 'Both';
        } elseif ($this->permanent_exhibition) {
            return 'Permanent';
        } elseif ($this->temporary_exhibition) {
            return 'Temporary';
        }
        return 'None';
    }

    public function getDurationAttribute(): string
    {
        if (!$this->start_date) return 'Indefinite';
        if (!$this->end_date) return 'From ' . $this->start_date->format('M d, Y');
        
        $days = $this->start_date->diffInDays($this->end_date);
        if ($days <= 30) {
            return $days . ' days';
        } elseif ($days <= 365) {
            return round($days / 30) . ' months';
        } else {
            return round($days / 365, 1) . ' years';
        }
    }

    // Mutators
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }
}
