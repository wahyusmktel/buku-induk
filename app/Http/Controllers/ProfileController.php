<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        $data = $request->only('name', 'email');

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            
            if (!$file->isValid()) {
                throw ValidationException::withMessages([
                    'avatar' => "Gagal mengunggah file: " . $file->getErrorMessage(),
                ]);
            }

            $imagePath = $file->getPathname();
            if (empty($imagePath) || !file_exists($imagePath)) {
                throw ValidationException::withMessages([
                    'avatar' => "File tidak ditemukan di direktori sementara.",
                ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $imageData = file_get_contents($imagePath);
            $sourceImage = @imagecreatefromstring($imageData);

            if ($sourceImage) {
                $width = imagesx($sourceImage);
                $height = imagesy($sourceImage);
                $maxDimension = 400; 
                
                $cropSize = min($width, $height);
                $targetImage = imagecreatetruecolor($maxDimension, $maxDimension);
                
                $white = imagecolorallocate($targetImage, 255, 255, 255);
                imagefill($targetImage, 0, 0, $white);
                
                imagecopyresampled(
                    $targetImage, $sourceImage,
                    0, 0,
                    ($width - $cropSize) / 2, ($height - $cropSize) / 2,
                    $maxDimension, $maxDimension,
                    $cropSize, $cropSize
                );

                ob_start();
                imagejpeg($targetImage, null, 75); 
                $processedImageData = ob_get_clean();
                
                imagedestroy($sourceImage);
                imagedestroy($targetImage);

                $fileName = 'avatars/' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $processedImageData);
                $data['avatar'] = $fileName;
            } else {
                // Fallback to standard store if image creation fails
                $path = $file->store('avatars', 'public');
                $data['avatar'] = $path;
            }
        }

        $user->update($data);

        ActivityLogService::log('profile_update', "Memperbarui profil: {$user->name}" . ($request->hasFile('avatar') ? " (Termasuk foto profil)" : ""));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'avatar_url' => $user->avatar_url
            ]);
        }

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
