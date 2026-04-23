<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Maksimal percobaan login sebelum dikunci
    private const MAX_ATTEMPTS = 5;

    // Durasi kunci setelah melebihi MAX_ATTEMPTS (detik) — 15 menit
    private const DECAY_SECONDS = 900;

    public function authenticate(Request $request)
    {
        // Redirect user yang sudah terautentikasi
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $this->ensureIsNotRateLimited($request);

        // Auth::attempt menggunakan Bcrypt/Argon2 secara otomatis — aman dari timing attack
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Cegah Session Fixation dengan regenerasi session ID
            $request->session()->regenerate();

            RateLimiter::clear($this->throttleKey($request));

            ActivityLogService::log('login', 'User berhasil login: ' . Auth::user()->name, [
                'email' => Auth::user()->email,
            ]);

            return redirect()->intended('/dashboard');
        }

        // Catat kegagalan ke rate limiter dengan decay eksplisit
        RateLimiter::hit($this->throttleKey($request), self::DECAY_SECONDS);

        // Pesan error generik — tidak membedakan "email tidak ada" vs "password salah"
        // untuk mencegah user enumeration
        throw ValidationException::withMessages([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ]);
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), self::MAX_ATTEMPTS)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        // Str::transliterate menormalisasi karakter Unicode lookalike (misal α→a)
        // sehingga variasi Unicode tidak bisa mem-bypass throttle per-email
        return Str::transliterate(Str::lower($request->input('email', ''))) . '|' . $request->ip();
    }

    public function logout(Request $request)
    {
        // Guard null: jika session sudah expired sebelum POST diterima,
        // Auth::user() bisa null dan akan menyebabkan fatal error
        if (Auth::check()) {
            ActivityLogService::log('logout', 'User telah logout: ' . Auth::user()->name);
        }

        Auth::logout();

        // Invalidasi session dan regenerasi CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
