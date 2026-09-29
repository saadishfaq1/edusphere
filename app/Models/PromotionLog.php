<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionLog extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'school_id', 'student_id', 'from_school_class_id', 'from_section_id',
        'to_school_class_id', 'to_section_id', 'action', 'promoted_by',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fromSchoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'from_school_class_id');
    }

    public function toSchoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'to_school_class_id');
    }
}