<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportStop extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'transport_route_id', 'name', 'pickup_time', 'sort_order'];

    protected $casts = ['pickup_time' => 'datetime:H:i'];

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }
}