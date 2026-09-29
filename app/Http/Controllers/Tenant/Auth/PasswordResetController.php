<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Services\Tenant\PasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(protected PasswordResetService $resets)
    {
    }

    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate(['email' => ['required', 'email']]);

        $token = $this->resets->createToken($validated['email']);

        if ($token) {
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $validated['email']]);

            Mail::raw("Click this link to reset your password: {$resetUrl}\n\nThis link expires in 60 minutes.", function ($message) use ($validated) {
                $message->to($validated['email'])->subject('Reset Your Password - '.app('tenant')->name);
            });
        }

        // Always show the same message, whether the email exists or not —
        // this prevents anyone from using this form to discover which emails are registered.
        return back()->with('success', 'If that email is registered, a reset link has been sent.');
    }

    public function showResetForm(Request $request): View
    {
        return view('auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! $this->resets->validateToken($validated['email'], $validated['token'])) {
            return back()->withErrors(['email' => 'This reset link is invalid or has expired. Please request a new one.']);
        }

        $this->resets->resetPassword($validated['email'], $validated['password']);

        return redirect()->route('login')->with('success', 'Your password has been reset. You can now sign in.');
    }
}