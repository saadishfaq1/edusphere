<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'source', 'amount', 'date', 'description', 'recorded_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }
}