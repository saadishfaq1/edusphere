<?php

namespace App\Services\Tenant;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function createToken(string $email): ?string
    {
        $school = app('tenant');

        $user = User::where('school_id', $school->id)->where('email', $email)->first();

        if (! $user) {
            return null;
        }

        $token = Str::random(64);

        DB::table('tenant_password_resets')->updateOrInsert(
            ['school_id' => $school->id, 'email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        return $token;
    }

    public function validateToken(string $email, string $token): bool
    {
        $school = app('tenant');

        $record = DB::table('tenant_password_resets')
            ->where('school_id', $school->id)
            ->where('email', $email)
            ->first();

        if (! $record) {
            return false;
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            return false;
        }

        return Hash::check($token, $record->token);
    }

    public function resetPassword(string $email, string $password): void
    {
        $school = app('tenant');

        User::where('school_id', $school->id)->where('email', $email)->update([
            'password' => Hash::make($password),
        ]);

        DB::table('tenant_password_resets')
            ->where('school_id', $school->id)
            ->where('email', $email)
            ->delete();
    }
}