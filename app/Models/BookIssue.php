<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BookIssue extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['school_id', 'book_id', 'borrower_type', 'borrower_id', 'issued_date', 'due_date', 'returned_date', 'status', 'issued_by'];

    protected function casts(): array
    {
        return ['issued_date' => 'date', 'due_date' => 'date', 'returned_date' => 'date'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrower(): MorphTo
    {
        return $this->morphTo();
    }
}