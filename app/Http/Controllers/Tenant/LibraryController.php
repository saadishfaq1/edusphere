<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Employee;
use App\Models\Student;
use App\Services\Tenant\LibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function __construct(protected LibraryService $library)
    {
    }

    public function books(): View
    {
        Gate::authorize('manage-library');

        return view('tenant.library.books', ['books' => $this->library->books()]);
    }

    public function storeBook(Request $request): RedirectResponse
    {
        Gate::authorize('manage-library');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:150'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'total_copies' => ['required', 'integer', 'min:1'],
        ]);

        $this->library->createBook($validated);

        return back()->with('success', 'Book added successfully.');
    }

    public function editBook(Request $request): View
    {
        Gate::authorize('manage-library');

        $book = Book::findOrFail($request->route('book'));

        return view('tenant.library.book-edit', compact('book'));
    }

    public function updateBook(Request $request): RedirectResponse
    {
        Gate::authorize('manage-library');

        $book = Book::findOrFail($request->route('book'));

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:150'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'total_copies' => ['required', 'integer', 'min:1'],
        ]);

        $this->library->updateBook($book, $validated);

        return redirect()->route('library.books')->with('success', 'Book updated successfully.');
    }

    public function issues(): View
    {
        Gate::authorize('manage-library');

        return view('tenant.library.issues', [
            'issues' => $this->library->issues(),
            'books' => $this->library->books(),
            'students' => Student::where('status', 'active')->orderBy('first_name')->get(),
            'employees' => Employee::where('status', 'active')->orderBy('first_name')->get(),
        ]);
    }

    public function storeIssue(Request $request): RedirectResponse
    {
        Gate::authorize('manage-library');

        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'borrower_type' => ['required', 'in:student,employee'],
            'borrower_id' => ['required', 'integer'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $book = Book::findOrFail($validated['book_id']);

        try {
            $this->library->issueBook($book, $validated['borrower_type'], (int) $validated['borrower_id'], $validated['due_date'], auth()->id());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Book issued successfully.');
    }

    public function returnBook(Request $request): RedirectResponse
    {
        Gate::authorize('manage-library');

        $issue = BookIssue::findOrFail($request->route('issue'));

        $this->library->returnBook($issue);

        return back()->with('success', 'Book marked as returned.');
    }
}