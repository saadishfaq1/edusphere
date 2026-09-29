<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'employee_id', 'month', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'status', 'paid_on'];

    protected function casts(): array
    {
        return ['paid_on' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}