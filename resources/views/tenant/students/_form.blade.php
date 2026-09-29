<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Class</label>
        <select name="school_class_id" class="form-control @error('school_class_id') is-invalid @enderror">
            <option value="">Select Class</option>
            @foreach (\App\Models\SchoolClass::orderBy('name')->get() as $class)
                <option value="{{ $class->id }}" @selected(old('school_class_id', $student->school_class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        @error('school_class_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Section</label>
        <select name="section_id" class="form-control @error('section_id') is-invalid @enderror">
            <option value="">Select Section</option>
            @foreach (\App\Models\Section::with('schoolClass')->get() as $section)
                <option value="{{ $section->id }}" @selected(old('section_id', $student->section_id ?? '') == $section->id)>{{ $section->full_name }}</option>
            @endforeach
        </select>
        @error('section_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Roll Number</label>
        <input type="text" name="roll_number" class="form-control @error('roll_number') is-invalid @enderror"
               value="{{ old('roll_number', $student->roll_number ?? '') }}">
        @error('roll_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
        @error('photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
        @if (isset($student) && $student->photo_path)
            <img src="{{ asset('storage/'.$student->photo_path) }}" alt="Current photo" class="mt-2 rounded" height="60">
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
               value="{{ old('first_name', $student->first_name ?? '') }}">
        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
               value="{{ old('last_name', $student->last_name ?? '') }}">
        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
               value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth->format('Y-m-d') : '') }}">
        @error('date_of_birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
            <option value="">Select</option>
            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}" @selected(old('gender', $student->gender ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Admission Date</label>
        <input type="date" name="admission_date" class="form-control @error('admission_date') is-invalid @enderror"
               value="{{ old('admission_date', isset($student) ? $student->admission_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
        @error('admission_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $student->email ?? '') }}">
        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $student->phone ?? '') }}">
        @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control @error('status') is-invalid @enderror">
            @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'graduated' => 'Graduated', 'transferred' => 'Transferred'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $student->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $student->address ?? '') }}</textarea>
    @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

<hr class="my-4">
<h5 class="mb-3">Guardian Information</h5>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Guardian Name</label>
        <input type="text" name="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror"
               value="{{ old('guardian_name', $student->guardian_name ?? '') }}">
        @error('guardian_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Relation</label>
        <input type="text" name="guardian_relation" class="form-control @error('guardian_relation') is-invalid @enderror"
               value="{{ old('guardian_relation', $student->guardian_relation ?? '') }}" placeholder="e.g. Father, Mother, Uncle">
        @error('guardian_relation') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Guardian Phone</label>
        <input type="text" name="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror"
               value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}">
        @error('guardian_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Guardian Email</label>
        <input type="email" name="guardian_email" class="form-control @error('guardian_email') is-invalid @enderror"
               value="{{ old('guardian_email', $student->guardian_email ?? '') }}">
        @error('guardian_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

