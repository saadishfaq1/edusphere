<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'billing_cycle', 'max_students', 'max_staff', 'modules', 'is_active'];

    protected function casts(): array
    {
        return ['modules' => 'array'];
    }

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }
}