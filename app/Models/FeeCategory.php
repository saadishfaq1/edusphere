<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'name', 'type', 'is_transport'];
}