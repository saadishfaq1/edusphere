<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'vehicle_number', 'driver_name', 'driver_phone', 'capacity'];

    public function routes(): HasMany
    {
        return $this->hasMany(TransportRoute::class);
    }
}