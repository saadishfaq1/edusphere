<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'name', 'discount_type', 'discount_value'];

    public function calculateDiscount(float $amount): float
    {
        return $this->discount_type === 'percentage'
            ? round($amount * ($this->discount_value / 100), 2)
            : min($this->discount_value, $amount);
    }
}