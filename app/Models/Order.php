<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'craftsman_id',
        'order_number',
        'products',
        'subtotal',
        'total',
        'tax',
        'discount',
        'status',
        'delivery_address',
        'delivery_city',
        'delivery_postal_code',
        'delivery_notes',
        'desired_delivery_date',
        'actual_delivery_date',
        'payment_method',
        'payment_reference',
        'paid',
        'shop_notes',
        'craftsman_notes',
    ];

    protected $casts = [
        'products' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid' => 'boolean',
        'desired_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
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
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paid', false);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeInPreparation($query)
    {
        return $query->where('status', 'in_preparation');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_preparation' => 'In Preparation',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => $this->status
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2, '.', ',') . '€';
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 2, '.', ',') . '€';
    }

    public function getProductsDetailsAttribute(): array
    {
        if (!$this->products) return [];
        
        $products = [];
        foreach ($this->products as $product) {
            $productModel = Product::find($product['product_id']);
            if ($productModel) {
                $products[] = [
                    'product' => $productModel,
                    'quantity' => $product['quantity'],
                    'adapted_price' => $product['adapted_price'],
                ];
            }
        }
        
        return $products;
    }

    public function getFullDeliveryAddressAttribute(): string
    {
        $parts = array_filter([
            $this->delivery_address,
            $this->delivery_postal_code,
            $this->delivery_city
        ]);
        return implode(', ', $parts);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->desired_delivery_date) {
            return false;
        }
        
        return $this->desired_delivery_date->isPast() && 
               !in_array($this->status, ['delivered', 'cancelled']);
    }

    // Mutators
    public function setOrderNumberAttribute($value)
    {
        if (!$value) {
            $this->attributes['order_number'] = 'ORD-' . strtoupper(uniqid());
        } else {
            $this->attributes['order_number'] = $value;
        }
    }
}
