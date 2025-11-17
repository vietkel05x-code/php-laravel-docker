<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'starts_at', 'ends_at', 'max_uses', 'uses',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function isValid()
    {
        $now = now();
        
        // Check date range
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }
        
        // Check usage limit
        if ($this->max_uses && $this->uses >= $this->max_uses) {
            return false;
        }
        
        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'percent') {
            return $amount * ($this->value / 100);
        }
        
        return min($this->value, $amount);
    }
}
