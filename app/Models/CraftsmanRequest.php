<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CraftsmanRequest extends Model
{
    use HasFactory;

    protected $table = 'craftsman_requests';

    protected $fillable = [
        'shop_id',
        'craftsman_id',
        'title',
        'description',
        'specifications',
        'requested_quantity',
        'estimated_budget',
        'deadline',
        'status',
        'craftsman_response',
        'proposed_price',
        'response_date',
    ];

    protected $casts = [
        'specifications' => 'array',
        'estimated_budget' => 'decimal:2',
        'proposed_price' => 'decimal:2',
        'deadline' => 'date',
        'response_date' => 'date',
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
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeUrgent($query)
    {
        return $query->where('deadline', '<=', now()->addDays(7));
    }

    // Accessors
    public function getFormattedBudgetAttribute(): string
    {
        return $this->estimated_budget ? number_format($this->estimated_budget, 2, '.', ',') . ' €' : 'Not specified';
    }

    public function getFormattedProposedPriceAttribute(): string
    {
        return $this->proposed_price ? number_format($this->proposed_price, 2, '.', ',') . ' €' : 'Not specified';
    }

    public function getIsUrgentAttribute(): bool
    {
        if (!$this->deadline) return false;
        return $this->deadline->diffInDays(now()) <= 7;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            default => $this->status
        };
    }

    public function getDaysUntilDeadlineAttribute(): int
    {
        if (!$this->deadline) return 0;
        return max(0, $this->deadline->diffInDays(now()));
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline) return false;
        return $this->deadline->isPast() && !in_array($this->status, ['completed', 'rejected']);
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst(strtolower($value));
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }
}
