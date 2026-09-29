@extends('layouts.tenant')
@section('title', 'Issue / Return Books')
@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Issue a Book</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                <form method="POST" action="{{ route('library.issues.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Book</label>
                        <select name="book_id" class="form-control" required>
                            <option value="">Select Book</option>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}" @disabled($book->available_copies < 1)>
                                    {{ $book->title }} ({{ $book->available_copies }} available)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borrower Type</label>
                        <select name="borrower_type" class="form-control" id="borrower-type" required>
                            <option value="student">Student</option>
                            <option value="employee">Staff / Employee</option>
                        </select>
                    </div>
                    <div class="mb-3" id="student-select">
                        <label class="form-label">Student</label>
                        <select name="borrower_id" class="form-control">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Issue Book</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Issued Books</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Book</th><th>Borrower</th><th>Due Date</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($issues as $issue)
                            <tr>
                                <td>{{ $issue->book->title }}</td>
                                <td>{{ $issue->borrower->full_name ?? '—' }}</td>
                                <td>{{ $issue->due_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $issue->status === 'returned' ? 'success' : 'warning' }}">
                                        {{ ucfirst($issue->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($issue->status === 'issued')
                                        <form method="POST" action="{{ route('library.issues.return', $issue) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-soft-success">Mark Returned</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No books issued yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const employeesData = @json($employees->map(fn($e) => ['id' => $e->id, 'name' => $e->full_name]));
    const typeSelect = document.getElementById('borrower-type');
    const studentDiv = document.getElementById('student-select');

    typeSelect.addEventListener('change', function () {
        if (this.value === 'employee') {
            let options = '<option value="">Select Employee</option>';
            employeesData.forEach(e => options += `<option value="${e.id}">${e.name}</option>`);
            studentDiv.querySelector('label').textContent = 'Employee';
            studentDiv.querySelector('select').innerHTML = options;
        } else {
            studentDiv.querySelector('label').textContent = 'Student';
            location.reload(); // simplest safe reset back to student list
        }
    });
</script>
@endsection

