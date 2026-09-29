<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'subdomain',
        'email',
        'phone',
        'address',
        'plan_id',
        'status',
        'trial_ends_at',
        'bank_name',
        'bank_account_title',
        'bank_account_number',
        'academic_year',
        'primary_color',
        'modules',
        'organization_id',
        'branch_name',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'modules' => 'array',
    ];

    public function hasModule(string $key): bool
    {
        // School-level override takes priority
        if ($this->modules !== null) {
            return in_array($key, $this->modules);
        }

        // Fall back to the plan's modules
        if ($this->plan && $this->plan->modules !== null) {
            return in_array($key, $this->plan->modules);
        }

        // No plan, no override → open access (backward compatible)
        return true;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}