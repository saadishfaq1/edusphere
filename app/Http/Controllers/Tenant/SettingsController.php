<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function profile(): View
    {
        Gate::authorize('manage-settings');

        return view('tenant.settings.profile', ['school' => app('tenant')]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:20'],
        ]);

        app('tenant')->update($validated);

        return back()->with('success', 'School profile updated.');
    }

    public function users(): View
    {
        Gate::authorize('manage-settings');

        return view('tenant.settings.users', [
            'users' => User::where('school_id', app('tenant')->id)->with('roles')->get(),
            'roles' => Role::pluck('name'),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => app('tenant')->id,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return back()->with('success', 'User created successfully.');
    }

    public function updateUserRole(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $user = User::where('school_id', app('tenant')->id)->findOrFail($request->route('user'));

        $validated = $request->validate(['role' => ['required', 'exists:roles,name']]);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'User role updated.');
    }

    public function toggleUserStatus(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $user = User::where('school_id', app('tenant')->id)->findOrFail($request->route('user'));

        if ($user->id === auth()->id()) {
            return back()->with('error', "You can't deactivate your own account.");
        }

        $user->update(['email_verified_at' => $user->email_verified_at ? null : now()]);

        return back()->with('success', 'User status updated.');
    }

    public function brand(): View
    {
        Gate::authorize('manage-settings');

        return view('tenant.settings.brand', ['school' => app('tenant')]);
    }

    public function updateBrand(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $school = app('tenant');

        if ($request->hasFile('logo')) {
            if ($school->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($school->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('school-logos', 'public');
        }

        unset($validated['logo']);

        $school->update($validated);

        return back()->with('success', 'Brand settings updated.');
    }

    public function myProfile(): View
    {
        return view('tenant.settings.my-profile', ['user' => auth()->user()]);
    }

    public function updateMyProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'photo' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo_path);
            }
            $user->photo_path = $request->file('photo')->store('user-photos', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['new_password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}