<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'title', 'author', 'isbn', 'category', 'total_copies', 'available_copies'];

    public function issues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }
}