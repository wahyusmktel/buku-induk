<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'Aktif');
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        
        $query = Siswa::query();
        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        $siswas = $query->latest()->paginate(15)->withQueryString();
        
        return view('siswas.index', compact('siswas', 'tahunAktif', 'status'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        Log::info('Import request received', [
            'has_file' => $request->hasFile('file'),
            'file_is_valid' => $file ? $file->isValid() : 'no file',
            'file_error' => $file ? $file->getError() : 'no file',
            'user' => auth()->user()->id
        ]);



        // Check Role
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            Log::warning('Unauthorized import attempt', ['user' => auth()->user()->email]);
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk melakukan import data.');
        }

        $request->validate([
            'file' => 'required'
        ]);

        // Check Active Tahun Pelajaran
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Gagal melakukan import: Tidak ada Tahun Pelajaran yang aktif. Silakan aktifkan tahun pelajaran terlebih dahulu di menu Tahun Pelajaran.');
        }

        try {
            $filename = time() . '_' . $file->getClientOriginalName();
            $tempDir = storage_path('app/private/temp');
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file->move($tempDir, $filename);
            $path = 'temp/' . $filename;
            
            $import = new SiswaImport;
            Excel::import($import, $path);
            
            Storage::delete($path);

            // --- AUTOMATIC GRADUATION / ARCHIVING LOGIC ---
            
            // 1. Current Session Students (just processed)
            $currentSessionIds = $import->processedSiswaIds;
            $currentSessionNisnNik = Siswa::whereIn('id', $currentSessionIds)
                ->get(['nisn', 'nik'])
                ->map(fn($s) => [$s->nisn, $s->nik])
                ->flatten()
                ->filter()
                ->toArray();

            // 2. Find Previous Session
            $previousSession = \App\Models\TahunPelajaran::where('id', '!=', $tahunAktif->id)
                ->where(function($q) use ($tahunAktif) {
                    $q->where('tahun', '<', $tahunAktif->tahun)
                      ->orWhere(function($sub) use ($tahunAktif) {
                          $sub->where('tahun', $tahunAktif->tahun)
                              ->where('semester', 'Ganjil'); // If current is Genap, previous is Ganjil
                      });
                })
                ->orderBy('tahun', 'desc')
                ->orderBy('semester', 'desc') // Genap (E) < Ganjil (A) in sorting? Wait, Ga-njil vs Ge-nap. Ga < Ge. So desc for Genap.
                ->first();

            $graduatedPrevCount = 0;
            $departedPrevCount = 0;

            if ($previousSession) {
                // Find students who were Aktif in the previous session
                $prevStudents = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $previousSession->id)
                    ->where('status', 'Aktif')
                    ->get();

                foreach ($prevStudents as $oldSiswa) {
                    // Check if this student exists in the CURRENT session
                    $existsInCurrent = false;
                    if ($oldSiswa->nisn && in_array($oldSiswa->nisn, $currentSessionNisnNik)) {
                        $existsInCurrent = true;
                    } elseif ($oldSiswa->nik && in_array($oldSiswa->nik, $currentSessionNisnNik)) {
                        $existsInCurrent = true;
                    }

                    if (!$existsInCurrent) {
                        // They are gone in the new session. Mark them in the PREVIOUS session.
                        if ($this->isJenjangAkhir($oldSiswa->rombel_saat_ini)) {
                            // 1. Mark as Lulus in the PREVIOUS session for archive
                            $oldSiswa->update(['status' => 'Lulus']);
                            $graduatedPrevCount++;

                            // 2. Also register them as Lulus in the CURRENT session (Tahun Terbaru)
                            // check if they already exist in current session (unlikely if missing from Excel)
                            $currentDuplicate = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)
                                ->where(function($q) use ($oldSiswa) {
                                    if ($oldSiswa->nisn) $q->where('nisn', $oldSiswa->nisn);
                                    if ($oldSiswa->nik) $q->orWhere('nik', $oldSiswa->nik);
                                })->first();

                            if (!$currentDuplicate) {
                                $newLulusRecord = $oldSiswa->replicate();
                                $newLulusRecord->tahun_pelajaran_id = $tahunAktif->id;
                                $newLulusRecord->status = 'Lulus';
                                $newLulusRecord->save();
                            } else {
                                $currentDuplicate->update(['status' => 'Lulus']);
                            }
                        } else {
                            $oldSiswa->update(['status' => 'Keluar/Mutasi']);
                            $departedPrevCount++;
                        }
                    }
                }
            }

            // 3. Current Session "Missing" (for cases like manual import/copy data)
            $unprocessedInCurrent = Siswa::whereNotIn('id', $currentSessionIds)->get();
            $graduatedCurrCount = 0;
            $departedCurrCount = 0;

            foreach ($unprocessedInCurrent as $siswa) {
                if ($this->isJenjangAkhir($siswa->rombel_saat_ini)) {
                    $siswa->update(['status' => 'Lulus']);
                    $graduatedCurrCount++;
                } else {
                    $siswa->update(['status' => 'Keluar/Mutasi']);
                    $departedCurrCount++;
                }
            }

            $message = "Import Dapodik berhasil diselesaikan. ";
            $message .= "Resume: {$import->createdCount} Siswa Baru, ";
            $message .= "{$import->updatedCount} Siswa diperbarui. ";
            
            if ($graduatedPrevCount > 0 || $departedPrevCount > 0) {
                $message .= "Arsip Sesi Lalu ({$previousSession->tahun}): {$graduatedPrevCount} Lulus, {$departedPrevCount} Keluar.";
            }
            
            if ($graduatedCurrCount > 0 || $departedCurrCount > 0) {
                $message .= " Arsip Sesi Ini: {$graduatedCurrCount} Lulus, {$departedCurrCount} Keluar.";
            }
            
            return redirect()->route('siswas.index')->with('success', $message);
        } catch (\Exception $e) {
            // Clean up if something went wrong but we have a path
            if (isset($path)) {
                Storage::delete($path);
            }
            
            Log::error('Import failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user' => auth()->user()->id
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan import: ' . $e->getMessage());
        }

    }

    public function show(Siswa $siswa)
    {
        return view('siswas.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }
        
        return view('siswas.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:10',
            'nik'  => 'nullable|string|max:16',
            'rombel_saat_ini' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Lulus,Keluar/Mutasi',
            // Add other core fields as needed
        ]);

        $siswa->update($validated);

        return redirect()->route('siswas.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $siswa->delete();
        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil dihapus.');
    }
    /**
     * Helper to detect if a Rombel is a final grade (Lulus)
     */
    private function isJenjangAkhir($rombelNama)
    {
        if (!$rombelNama) return false;
        
        $name = strtolower($rombelNama);
        
        // 1. Cek status eksplisit
        $explicitPatterns = ['lulus', 'alumni', 'tamat'];
        foreach ($explicitPatterns as $p) {
            if (str_contains($name, $p)) return true;
        }

        /**
         * 2. Logika Deteksi Angka Jenjang (6, 9, 12)
         * Regex ini mencari angka 6, 9, atau 12 yang:
         * - Berada di awal string (^6...)
         * - Diawali spasi atau titik (.6... / 6...)
         * - Diikuti huruf, spasi, titik, atau akhir string (6c, 6.a, 6-b, 6 )
         * - Tapi bukan bagian dari angka lain (misal 16)
         */
        $finalGrades = ['6', '9', '12', 'vi', 'ix', 'xii'];
        foreach ($finalGrades as $grade) {
            // Regex: \b (word boundary) or start of string
            // Diikuti oleh $grade
            // Diikuti oleh karakter non-angka atau akhir string
            if (preg_match('/(^|[\s\.])' . preg_quote($grade, '/') . '([^0-9]|$)/i', $name)) {
                return true;
            }
        }

        // 3. Cek frase 'kelas x' (untuk romawi atau angka)
        $phrases = ['kelas 6', 'kelas vi', 'kelas 9', 'kelas ix', 'kelas 12', 'kelas xii'];
        foreach ($phrases as $phrase) {
            if (str_contains($name, $phrase)) return true;
        }

        return false;
    }
}
