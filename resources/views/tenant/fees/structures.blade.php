@extends('layouts.tenant')
@section('title', 'Generate Fees')
@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Generate Fees for a Class</h4>
                <p class="text-muted">Select the class, tick the fee categories that apply (e.g. Tuition, Bus), enter each amount, and generate. Invoices are created for every active student, and a printable fee slip opens automatically.</p>

                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <form method="POST" action="{{ route('fees.structures.generate') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="school_class_id" class="form-control" required>
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. August 2026 Fees" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <label class="form-label d-block">Fee Categories</label>
                    <p class="text-muted font-size-13">Transport fee is added automatically for students who have a bus stop assigned — no need to select it here.</p>
                    @forelse ($categories->where('is_transport', false) as $category)
                        <div class="row align-items-center mb-2">
                            <div class="col-1">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input">
                            </div>
                            <div class="col-6">{{ $category->name }}</div>
                            <div class="col-5">
                                <input type="number" step="0.01" name="amounts[{{ $category->id }}]" class="form-control" placeholder="Amount">
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No fee categories yet — add one first under Fee Categories.</p>
                    @endforelse

                    <button type="submit" class="btn btn-primary mt-3">Generate &amp; Print Fee Slips</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Current Fee Structure</h4></div>
            <div class="card-body">
                <table class="table table-centered align-middle table-nowrap mb-0">
                    <thead><tr><th>Class</th><th>Category</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($structures as $structure)
                            <tr>
                                <td>{{ $structure->schoolClass->name }}</td>
                                <td>{{ $structure->feeCategory->name }}</td>
                                <td>{{ number_format($structure->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No fees generated yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

