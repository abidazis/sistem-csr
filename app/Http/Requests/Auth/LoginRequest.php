<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // UBAH BAGIAN INI DARI 'email' MENJADI 'npk'
        return [
            'npk' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // UBAH BAGIAN INI, GUNAKAN 'npk' UNTUK OTENTIKASI
        if (! Auth::attempt($this->only('npk', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // UBAH BAGIAN INI, TAMPILKAN ERROR PADA FIELD 'npk'
            throw ValidationException::withMessages([
                'npk' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // UBAH BAGIAN INI, TAMPILKAN ERROR PADA FIELD 'npk'
        throw ValidationException::withMessages([
            'npk' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        // Bagian ini sudah benar
        return Str::transliterate(Str::lower($this->input('npk')).'|'.$this->ip());
    }
}