<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Class</label>
        <select name="school_class_id" class="form-control @error('school_class_id') is-invalid @enderror">
            <option value="">Select Class</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}" @selected(old('school_class_id', $section->school_class_id ?? '') == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        @error('school_class_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Section Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $section->name ?? '') }}" placeholder="e.g. A">
        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
               value="{{ old('capacity', $section->capacity ?? '') }}" placeholder="e.g. 40">
        @error('capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

