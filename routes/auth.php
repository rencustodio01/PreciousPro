<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // ── Registration ─────────────────────────────────
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // ── Login ────────────────────────────────────────
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:10,1');

    // ── Password Reset Flow ──────────────────────────
    Route::get('forgot-password', [PasswordResetCodeController::class, 'showEmailForm'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetCodeController::class, 'sendCode'])
        ->name('password.email');

    Route::get('verify-code', [PasswordResetCodeController::class, 'showVerifyForm'])
        ->name('password.verify.form');

    Route::post('verify-code', [PasswordResetCodeController::class, 'verifyCode'])
        ->name('password.verify');

    Route::get('reset-password-new', [PasswordResetCodeController::class, 'showResetForm'])
        ->name('password.reset.form');

    Route::post('reset-password-new', [PasswordResetCodeController::class, 'resetPassword'])
        ->name('password.reset.new');

    Route::post('resend-code', [PasswordResetCodeController::class, 'resendCode'])
        ->name('password.resend');
});

Route::middleware('auth')->group(function () {

    // ── Pending Approval Page ────────────────────────
    Route::get('pending', function () {
        // If user already has a role, redirect them away from pending page
        if (auth()->user()->role_id) {
            return redirect()->route('dashboard');
        }
        return view('auth.pending');
    })->name('pending');

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});