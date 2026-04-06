<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman form pengaturan sistem
     */
    public function index()
    {
        // Ambil semua setting menjadi format array key-value yang gampang dibaca di blade
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    /**
     * Simpan update konfigurasi
     */
    public function update(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kepsek_nama' => 'nullable|string|max:255',
            'kepsek_nip' => 'nullable|string|max:255',
            'buku_induk_kota' => 'nullable|string|max:255',
            'buku_induk_tanggal' => 'nullable|string|max:255',
            'kepsek_ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sekolah_stempel' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sekolah_kop' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sekolah_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan inputan string biasa
        $textSettings = $request->except(['_token', '_method', 'kepsek_ttd', 'sekolah_stempel', 'sekolah_kop', 'sekolah_logo']);

        foreach ($textSettings as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        // Simpan file upload (gambar)
        $files = [
            'kepsek_ttd', 'sekolah_stempel', 'sekolah_kop', 'sekolah_logo'
        ];

        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Ambil file setting lama untuk bisa dihapus agar storage tidak penuh
                $oldSetting = Setting::where('key', $fileKey)->first();

                if ($oldSetting && $oldSetting->value) {
                    if (Storage::disk('public')->exists($oldSetting->value)) {
                        Storage::disk('public')->delete($oldSetting->value);
                    }
                }

                $path = $request->file($fileKey)->store('settings', 'public');
                Setting::updateOrCreate(['key' => $fileKey], ['value' => $path]);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Konfigurasi Sistem Berhasil Diperbarui!');
    }
}
