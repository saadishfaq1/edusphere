<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'school_id',
        'school_class_id',
        'name',
        'capacity',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->schoolClass->name} - {$this->name}";
    }
}