<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Class Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $class->name ?? '') }}" placeholder="e.g. Grade 10">
        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
               value="{{ old('sort_order', $class->sort_order ?? 0) }}">
        @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
        <small class="text-muted">Lower numbers appear first in lists.</small>
    </div>
</div>

