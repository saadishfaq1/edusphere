@extends('layouts.central')
@section('title', $school->name)
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ $school->name }}</h4>
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                <table class="table table-borderless mb-4">
                    <tr><td>Subdomain</td><td class="text-end">{{ $school->subdomain }}.edusphere.test</td></tr>
                    <tr><td>Email</td><td class="text-end">{{ $school->email ?? '—' }}</td></tr>
                    <tr><td>Phone</td><td class="text-end">{{ $school->phone ?? '—' }}</td></tr>
                    <tr><td>Students</td><td class="text-end">{{ $studentCount }} {{ $school->plan?->max_students ? '/ '.$school->plan->max_students : '' }}</td></tr>
                    <tr><td>Trial Ends</td><td class="text-end">{{ $school->trial_ends_at?->format('d M Y') ?? '—' }}</td></tr>
                </table>

                <h5 class="mb-2">Change Status</h5>
                <form method="POST" action="{{ route('central.schools.status', $school) }}" class="d-flex gap-2 mb-4">
                    @csrf
                    <select name="status" class="form-control">
                        @foreach (['trial' => 'Trial', 'active' => 'Active', 'suspended' => 'Suspended'] as $value => $label)
                            <option value="{{ $value }}" @selected($school->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary">Update</button>
                </form>

                <h5 class="mb-2 mt-4">Extend Trial</h5>
                <form method="POST" action="{{ route('central.schools.extend-trial', $school) }}" class="d-flex gap-2">
                    @csrf
                    <input type="number" name="days" class="form-control" placeholder="Days to add" min="1" value="14">
                    <button class="btn btn-primary">Extend</button>
                </form>

                <h5 class="mb-2">Assign Plan</h5>
                <form method="POST" action="{{ route('central.schools.plan', $school) }}" class="d-flex gap-2">
                    @csrf
                    <select name="plan_id" class="form-control">
                        <option value="">No plan</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" @selected($school->plan_id === $plan->id)>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary">Assign</button>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-2">Module Access Override</h5>
                <p class="text-muted font-size-13">By default this school uses its plan's modules. Check "Custom access" to restrict this specific school instead.</p>

                <form method="POST" action="{{ route('central.schools.modules', $school) }}">
                    @csrf
                    <div class="form-check mb-2">
                        <input type="checkbox" name="custom" value="1" class="form-check-input" id="custom-toggle"
                            @checked($school->modules !== null) onchange="document.getElementById('module-list').style.display = this.checked ? 'block' : 'none'">
                        <label class="form-check-label" for="custom-toggle">Custom access for this school</label>
                    </div>

                    <div id="module-list" style="display: {{ $school->modules !== null ? 'block' : 'none' }};">
                        @foreach (config('modules') as $key => $label)
                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="{{ $key }}" class="form-check-input" id="school-mod-{{ $key }}"
                                    @checked($school->modules && in_array($key, $school->modules))>
                                <label class="form-check-label" for="school-mod-{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save Module Access</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3 border-danger">
    <div class="card-body">
        <h5 class="text-danger mb-2">Danger Zone</h5>
        <p class="text-muted font-size-13">Deleting this school permanently removes all its students, staff, fees, and records. This cannot be undone.</p>
        <button type="button" class="btn btn-danger"
            onclick="confirmDelete('{{ route('central.schools.destroy', $school) }}', 'Delete {{ $school->name }}?', 'This permanently removes all its students, staff, fees, and records. This is irreversible.')">
            Delete This School
        </button>
    </div>
</div>
@endsection


