<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\ActivityLogService;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        ActivityLogService::log('profile_update', "Memperbarui profil: {$user->name}");

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function settings()
    {
        // Generate initial captcha if not exists
        if (!session()->has('captcha')) {
            $this->generateCaptcha();
        }

        return view('profile.settings', [
            'user' => Auth::user()
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
            'captcha' => 'required'
        ]);

        // Check Captcha
        if (strtolower($request->captcha) !== strtolower(session('captcha'))) {
            throw ValidationException::withMessages([
                'captcha' => 'Kode keamanan (Captcha) tidak cocok.',
            ]);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Kata sandi saat ini tidak cocok.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        ActivityLogService::log('password_change', "Mengubah kata sandi akun");

        // Refresh captcha after attempt
        $this->generateCaptcha();

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    public function captchaImage()
    {
        $code = $this->generateCaptcha();
        
        $width = 120;
        $height = 45;
        $image = imagecreatetruecolor($width, $height);
        
        $bg = imagecolorallocate($image, 248, 250, 252); // slate-50
        imagefill($image, 0, 0, $bg);
        
        // Random lines for noise
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, rand(150, 220), rand(150, 220), rand(150, 220));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }
        
        $textColor = imagecolorallocate($image, 79, 70, 229); // indigo-600
        $fontSize = 5;
        
        // Center text roughly
        $x = ($width - (strlen($code) * imagefontwidth($fontSize))) / 2;
        $y = ($height - imagefontheight($fontSize)) / 2;
        
        imagestring($image, $fontSize, $x, $y, $code, $textColor);
        
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        // Ensure session is saved before returning
        session()->save();
        
        return response($imageData)->header('Content-Type', 'image/png');
    }

    private function generateCaptcha()
    {
        $code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5);
        session(['captcha' => $code]);
        return $code;
    }
}
