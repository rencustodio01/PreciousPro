<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordResetCodeController extends Controller
{
    // ── Step 1: Show email form ─────────────────────────
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    // ── Step 2: Find account by email ──────────────────
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with that email address.',
        ]);

        // Store email in session, redirect to credential verification
        session(['reset_email' => $request->email]);

        return redirect()->route('password.verify.form');
    }

    // ── Step 3: Show credential verification form ───────
    public function showVerifyForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-code');
    }

    // ── Step 4: Verify credentials ──────────────────────
    public function verifyCode(Request $request)
    {
        $request->validate([
            'full_name'  => ['required', 'string'],
            'birthday'   => ['required', 'date'],
            'birthplace' => ['required', 'string'],
        ], [
            'full_name.required'  => 'Full name is required.',
            'birthday.required'   => 'Birthday is required.',
            'birthplace.required' => 'Birthplace is required.',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['full_name' => 'Session expired. Please start again.']);
        }

        $user = User::where('email', $email)->first();

        // Check all three credentials match
        $nameMatch      = strtolower(trim($user->full_name))  === strtolower(trim($request->full_name));
        $birthdayMatch  = $user->birthday && $user->birthday->format('Y-m-d') === date('Y-m-d', strtotime($request->birthday));
        $birthplaceMatch = strtolower(trim($user->birthplace)) === strtolower(trim($request->birthplace));

        if (!$nameMatch || !$birthdayMatch || !$birthplaceMatch) {
            Log::channel('daily')->warning('Failed Credential Verification for Password Reset', [
                'email'     => $email,
                'ip'        => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return back()->withErrors([
                'full_name' => 'The credentials you entered do not match our records. Please check and try again.',
            ]);
        }

        // Mark as verified
        session(['reset_code_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    // ── Step 5: Show new password form ──────────────────
    public function showResetForm()
    {
        if (!session('reset_email') || !session('reset_code_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-new');
    }

    // ── Step 6: Save new password ────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $email = session('reset_email');

        if (!$email || !session('reset_code_verified')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget(['reset_email', 'reset_code_verified']);

        Log::channel('daily')->info('Password Reset Successful', [
            'email'     => $email,
            'ip'        => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. Please login with your new password.');
    }

    // ── Resend (not needed anymore, kept for route compatibility) ──
    public function resendCode(Request $request)
    {
        return redirect()->route('password.verify.form');
    }
}