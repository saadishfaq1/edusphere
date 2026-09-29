<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name ?? '') }}">
        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name ?? '') }}">
        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $employee->designation ?? '') }}" placeholder="e.g. Math Teacher">
        @error('designation') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date of Joining</label>
        <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror" value="{{ old('date_of_joining', isset($employee) ? $employee->date_of_joining->format('Y-m-d') : '') }}">
        @error('date_of_joining') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Basic Salary</label>
        <input type="number" step="0.01" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', $employee->basic_salary ?? '') }}">
        @error('basic_salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'terminated' => 'Terminated'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $employee->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Linked Login Account (optional)</label>
        <select name="user_id" class="form-control">
            <option value="">Not linked to a login</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(old('user_id', $employee->user_id ?? '') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        <small class="text-muted">Link this employee to their staff login so they can see their own schedule, if they're a Teacher.</small>
    </div>
</div>

