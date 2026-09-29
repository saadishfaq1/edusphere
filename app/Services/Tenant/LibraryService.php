<?php

namespace App\Services\Tenant;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Employee;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LibraryService
{
    public function books(): Collection
    {
        return Book::orderBy('title')->get();
    }

    public function createBook(array $data): Book
    {
        $data['available_copies'] = $data['total_copies'];

        return Book::create($data);
    }

    public function updateBook(Book $book, array $data): Book
    {
        $diff = $data['total_copies'] - $book->total_copies;
        $data['available_copies'] = max(0, $book->available_copies + $diff);

        $book->update($data);

        return $book->fresh();
    }

    public function issues(): Collection
    {
        return BookIssue::with(['book', 'borrower'])->latest()->get();
    }

    public function issueBook(Book $book, string $borrowerType, int $borrowerId, string $dueDate, int $issuedBy): BookIssue
    {
        if ($book->available_copies < 1) {
            throw new \Exception('No copies available for this book.');
        }

        $borrowerModel = $borrowerType === 'student' ? Student::class : Employee::class;

        $book->decrement('available_copies');

        return BookIssue::create([
            'book_id' => $book->id,
            'borrower_type' => $borrowerModel,
            'borrower_id' => $borrowerId,
            'issued_date' => now()->format('Y-m-d'),
            'due_date' => $dueDate,
            'status' => 'issued',
            'issued_by' => $issuedBy,
        ]);
    }

    public function returnBook(BookIssue $issue): BookIssue
    {
        $issue->update(['status' => 'returned', 'returned_date' => now()->format('Y-m-d')]);

        $issue->book->increment('available_copies');

        return $issue->fresh();
    }
}