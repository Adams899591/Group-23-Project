<?php

// FILE: app/Livewire/Auth/Login.php
// Admin login. Admins are normal rows in Laravel's own `users` table (the old admin table is gone).

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Login')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;      // the design shows "Remember me" ticked by default

    // Validation rules (messages are shown under the fields)
    protected function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ];
    }

    public function login()
    {
        $this->validate();

        // 1) Brute-force protection: max 5 wrong attempts per email + IP, then wait
        $this->ensureIsNotRateLimited();

        // 2) Try to log in with Laravel's default guard (users table; password is checked against the bcrypt hash)
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            // Same message for "no such email" and "wrong password", so nobody can discover which emails exist
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // 3) Success: clear the counter, protect against session fixation, go where the user wanted (or the dashboard)
        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        return $this->redirectIntended(default: '/fudma/dashboard', navigate: true);
    }

    // Stops the form after 5 failed attempts
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
        ]);
    }

    // One counter per email + IP address
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth.app');
    }
}