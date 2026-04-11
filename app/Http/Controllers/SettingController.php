<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

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
            'sekolah_nama' => 'nullable|string|max:255',
            'jenjang_pendidikan' => 'nullable|string|in:SD,SMP,SMA/SMK',
            'kepsek_nama' => 'nullable|string|max:255',
            'kepsek_nip' => 'nullable|string|max:255',
            'buku_induk_kota' => 'nullable|string|max:255',
            'buku_induk_tanggal' => 'nullable|string|max:255',
            'kepsek_ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sekolah_stempel' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sekolah_kop' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sekolah_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Pengaturan Kertas
            'paper_size' => 'nullable|string|in:a4,folio,legal,letter,custom',
            'paper_width' => 'nullable|numeric|min:10',
            'paper_height' => 'nullable|numeric|min:10',
            'margin_top' => 'nullable|numeric|min:0',
            'margin_right' => 'nullable|numeric|min:0',
            'margin_bottom' => 'nullable|numeric|min:0',
            'margin_left' => 'nullable|numeric|min:0',
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

        // Pastikan folder settings ada
        if (!Storage::disk('public')->exists('settings')) {
            Storage::disk('public')->makeDirectory('settings');
        }

        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                
                if ($file && $file->isValid()) {
                    // Ambil file setting lama untuk bisa dihapus agar storage tidak penuh
                    $oldSetting = Setting::where('key', $fileKey)->first();

                    if ($oldSetting && !empty($oldSetting->value)) {
                        try {
                            if (Storage::disk('public')->exists($oldSetting->value)) {
                                Storage::disk('public')->delete($oldSetting->value);
                            }
                        } catch (\Exception $e) {
                            // Abaikan error jika file lama tidak ditemukan atau path bermasalah
                        }
                    }

                    // Simpan file baru
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(storage_path('app/public/settings'), $filename);
                    $path = 'settings/' . $filename;

                    Setting::updateOrCreate(['key' => $fileKey], ['value' => $path]);
                }
            }
        }

        ActivityLogService::log('system_settings_update', "Memperbarui Konfigurasi Sistem", [
            'updated_at' => now()
        ]);

        return redirect()->route('settings.index')->with('success', 'Konfigurasi Sistem Berhasil Diperbarui!');
    }
}
