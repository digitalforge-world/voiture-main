<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingCoupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'current_uses',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2'
    ];

    public function isValid()
    {
        if (!$this->is_active)
            return false;

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at))
            return false;
        if ($this->expires_at && $now->gt($this->expires_at))
            return false;

        if ($this->max_uses && $this->current_uses >= $this->max_uses)
            return false;

        return true;
    }

    public function getDiscountAmount($subtotal)
    {
        if ($this->type === 'percentage') {
            return $subtotal * ($this->value / 100);
        }
        return $this->value;
    }
}
