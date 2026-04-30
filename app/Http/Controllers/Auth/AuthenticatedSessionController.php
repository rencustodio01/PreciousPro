<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        if (!session()->has('captcha_a')) {
            session(['captcha_a' => rand(1, 9), 'captcha_b' => rand(1, 9)]);
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
 
        $a = session('captcha_a');
        $b = session('captcha_b');

        if ((int) $request->captcha !== ($a + $b)) {
            session(['captcha_a' => rand(1, 9), 'captcha_b' => rand(1, 9)]);

            Log::channel('daily')->warning('Failed Captcha Attempt', [
                'email'     => $request->email,
                'ip'        => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return back()
                ->withErrors(['captcha' => 'Incorrect security answer. Please try again.'])
                ->withInput($request->except('password', 'captcha'));
        }
        session(['captcha_a' => rand(1, 9), 'captcha_b' => rand(1, 9)]);

        try {
            $request->authenticate();
        } catch (\Exception $e) {
            Log::channel('daily')->warning('Failed Login Attempt', [
                'email'     => $request->email,
                'ip'        => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            throw $e;
        }

        Log::channel('daily')->info('Successful Login', [
            'user_id'   => auth()->id(),
            'user'      => auth()->user()->full_name,
            'role'      => auth()->user()->role?->role_name ?? 'No Role',
            'email'     => $request->email,
            'ip'        => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $request->session()->regenerate();


        if (!auth()->user()->role_id) {
            return redirect()->route('pending');
        }


        $role = auth()->user()->role?->role_name;

        return redirect()->intended(match($role) {
            'Admin'              => route('dashboard'),
            'Production Manager' => route('production.index'),
            'QC Officer'         => route('quality.index'),
            'Inventory Officer'  => route('inventory.index'),
            'Finance Officer'    => route('finance.index'),
            default              => route('dashboard'),
        });
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (auth()->check()) {
            Log::channel('daily')->info('User Logged Out', [
                'user_id'   => auth()->id(),
                'user'      => auth()->user()->full_name,
                'ip'        => $request->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}




















































































































































































































































































































































































































































































































































































