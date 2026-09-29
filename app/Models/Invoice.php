<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'school_id', 'student_id', 'fee_category_id', 'title',
        'base_amount', 'discount_amount', 'fine_amount', 'paid_amount', 'due_date', 'status',
    ];

    protected function casts(): array
    {
        return ['due_date' => 'date'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalDueAttribute(): float
    {
        return round($this->base_amount - $this->discount_amount + $this->fine_amount, 2);
    }

    public function getBalanceAttribute(): float
    {
        return round($this->total_due - $this->paid_amount, 2);
    }
}