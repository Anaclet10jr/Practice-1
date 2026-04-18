<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ── Show register form ────────────────────────────────────────
    public function showRegister(): View
    {
        return view('auth.register');
    }

    // ── Handle registration ───────────────────────────────────────
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:agent,client'],
            'phone'           => ['nullable', 'string', 'max:20'],
            // Agent-only
            'agency_name'     => ['required_if:role,agent', 'nullable', 'string', 'max:255'],
            'license_number'  => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'phone'          => $request->phone,
            'agency_name'    => $request->agency_name,
            'license_number' => $request->license_number,
            // Agents need admin approval before they can list properties
            'is_approved'    => $request->role === User::ROLE_CLIENT,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->redirectAfterAuth($user);
    }

    // ── Show login form ───────────────────────────────────────────
    public function showLogin(): View
    {
        return view('auth.login');
    }

    // ── Handle login ──────────────────────────────────────────────
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => __('auth.failed')]);
        }

        $request->session()->regenerate();

        return $this->redirectAfterAuth(Auth::user());
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ── Role-based redirect ───────────────────────────────────────
    private function redirectAfterAuth(User $user): RedirectResponse
    {
        return match ($user->role) {
            User::ROLE_ADMIN  => redirect()->route('admin.dashboard'),
            User::ROLE_AGENT  => redirect()->route('agent.dashboard'),
            default           => redirect()->route('home'),
        };
    }
}
