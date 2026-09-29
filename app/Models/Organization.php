<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'contact_email', 'contact_phone'];

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }
}