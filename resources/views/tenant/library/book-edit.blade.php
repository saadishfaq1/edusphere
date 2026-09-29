@extends('layouts.tenant')
@section('title', 'Edit Book')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Book</h4>
                <form method="POST" action="{{ route('library.books.update', $book) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}">
                        @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $book->category) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Copies</label>
                        <input type="number" name="total_copies" class="form-control" value="{{ old('total_copies', $book->total_copies) }}" min="1">
                        <small class="text-muted">Currently {{ $book->available_copies }} available (of {{ $book->total_copies }} total).</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                    <a href="{{ route('library.books') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

