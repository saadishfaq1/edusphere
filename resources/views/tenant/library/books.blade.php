@extends('layouts.tenant')
@section('title', 'Books')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Add Book</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('library.books.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                        @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author') }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Copies</label>
                        <input type="number" name="total_copies" class="form-control" value="{{ old('total_copies', 1) }}" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Book</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Books</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($books as $book)
                            <tr>
                                <td class="fw-semibold">{{ $book->title }}</td>
                                <td>{{ $book->author ?? '—' }}</td>
                                <td>{{ $book->category ?? '—' }}</td>
                                <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
                                <td><a href="{{ route('library.books.edit', $book) }}" class="btn btn-sm btn-soft-primary">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No books yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

