<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = ['logo_path', 'primary_color'];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}