# Dokumen Pengembangan Lanjutan — Buku Induk Digital

> **Dibuat**: 18 April 2026  
> **Diperbarui**: 19 April 2026  
> **Status Aplikasi**: Beta — Hampir Produksi (8 fitur tersisa)  
> **Branch Aktif**: `versi-client`  
> **Tech Stack**: Laravel 13, PHP 8.3, MySQL, Redis, Tailwind CSS v4, Alpine.js  

---

## Ringkasan Status Aplikasi Saat Ini

Aplikasi **Buku Induk Digital** adalah sistem manajemen data induk siswa sekolah berbasis web. Fungsi inti sudah berjalan dengan baik: manajemen siswa, buku induk, rombongan belajar, tahun pelajaran, prestasi belajar, dan ekstrakurikuler. RBAC via Spatie Permission sudah terstruktur. Import/export via Excel (Dapodik) sudah ada. Audit log dan bulk export async (queue Redis) sudah berfungsi.

**Yang sudah selesai & berjalan:**
- CRUD Data Pokok Siswa + import Dapodik
- Buku Induk (view, edit, print A4)
- Prestasi Belajar (nilai rapor per kelas/semester, import Excel)
- Prestasi Ekstrakurikuler
- Rombongan Belajar (assign siswa, copy dari semester lain, wali kelas)
- Promosi Siswa (naik semester, naik kelas)
- Tahun Pelajaran (aktivasi, copy data)
- User & Role Management (Spatie Permission)
- Audit Log + Export Excel
- Bulk Export ZIP (async queue)
- Dashboard statistik + distribusi tingkat + warning badges
- ✅ Artisan command `buku-induk:generate` (GenerateBukuIndukCommand)
- ✅ Upload foto buku induk (foto_1, foto_2 — UI + backend)
- ✅ Link navigasi silang (Siswa ↔ Buku Induk ↔ Rombel)
- ✅ Progress kelengkapan buku induk (progress bar di show.blade.php)
- ✅ Laporan & Statistik (`/laporan` — LaporanController + statistik.blade.php + AlumniExport)
- ✅ Cetak Dokumen (CetakController: surat aktif, surat lulus, leger)
- ✅ Global Search (SearchController + header search box Alpine.js)
- ✅ Soft Deletes (siswas + buku_induks — migration + SoftDeletes trait)
- ✅ Wali Kelas per Rombel (`nama_wali_kelas` di rombels — migration + UI)
- ✅ Eager Loading (SiswaController::index/show dengan with())

**Yang masih kurang / perlu diperbaiki:**
Lihat bagian-bagian di bawah ini, diurutkan berdasarkan prioritas.

---

## PRIORITAS 1 — Perbaikan Kritis ✅ SELESAI

### 1.1 Generate BukuInduk untuk Siswa Existing ✅

**Masalah**: Model `Siswa` hanya men-trigger auto-create `BukuInduk` pada event `created` (listener di model). Siswa yang sudah ada di database sebelum fitur buku induk diimplementasi tidak memiliki record `BukuInduk`.

**Solusi**: Buat artisan command untuk generate massal.

```bash
# Command yang perlu dibuat:
php artisan buku-induk:generate
```

**File yang perlu dibuat**: `app/Console/Commands/GenerateBukuIndukCommand.php`

**Logika**:
```php
// Loop semua Siswa yang punya NISN tapi belum ada BukuInduk
Siswa::whereNotNull('nisn')
    ->whereDoesntHave('bukuInduk') // atau cek via BukuInduk::whereNisn
    ->each(function ($siswa) {
        BukuInduk::firstOrCreate(['nisn' => $siswa->nisn], [
            'nama_panggilan' => ...,
            // fill dari data siswa yang tersedia
        ]);
    });
```

**Catatan**: Perlu cek relasi `Siswa → BukuInduk` lewat NISN (bukan FK langsung), karena BukuInduk bersifat permanen lintas tahun.

---

### 1.2 Upload Foto di Buku Induk ✅

**Masalah**: Kolom `foto_1` dan `foto_2` sudah ada di tabel `buku_induks` (migration sudah ada), tapi tidak ada UI untuk upload foto di halaman edit buku induk.

**File terkait**:
- `resources/views/buku-induk/edit.blade.php` — perlu tambah section upload foto
- `app/Http/Controllers/BukuIndukController.php` → method `update()` — perlu handle file upload
- `public/storage/buku-induk/foto/` — direktori target storage

**Referensi implementasi**: Lihat cara upload avatar di `app/Http/Controllers/ProfileController.php` — sudah ada logika compress & crop gambar menggunakan GD. Gunakan pola yang sama.

**Spesifikasi foto**:
- Foto 1: Foto terbaru siswa (wajah)
- Foto 2: Foto tambahan (opsional)
- Format: JPEG, max 2MB sebelum compress
- Resize ke maksimal 400x600px (potret)
- Simpan ke `storage/app/public/buku-induk/foto/{nisn}_1.jpg`

---

### 1.3 Link Antar Halaman (Navigasi Silang) ✅

**Masalah**: Tidak ada link langsung dari halaman detail siswa ke buku induknya, atau dari halaman rombel ke buku induk anggota.

**Perbaikan yang dibutuhkan**:

| Dari Halaman | Tambahkan Tombol/Link |
|---|---|
| `siswas/show.blade.php` | "Lihat Buku Induk" → `/buku-induk/{nisn}` |
| `siswas/index.blade.php` (baris tabel) | Ikon link ke buku induk di kolom aksi |
| `rombels/show.blade.php` (list anggota) | Kolom aksi dengan link ke buku induk masing-masing siswa |
| `buku-induk/show.blade.php` | "Lihat Data Pokok Siswa" → `/siswas/{id}` |
| `alumni/index.blade.php` | Link ke buku induk alumni |

---

### 1.4 Validasi Kelengkapan Data di Detail Siswa ✅

**Masalah**: Accessor `getKelengkapanAttribute()` sudah ada di model `BukuInduk` (menghitung persentase kelengkapan 0–100%), tapi tidak ditampilkan di halaman `buku-induk/show.blade.php` maupun `siswas/show.blade.php`.

**Implementasi**:
- Tampilkan progress bar kelengkapan di `buku-induk/show.blade.php` bagian header
- Tampilkan daftar field mana yang masih kosong (checklist) agar operator tahu apa yang perlu dilengkapi
- Di `buku-induk/index.blade.php`, kolom "Kelengkapan" sudah ada — pastikan nilainya benar

---

## PRIORITAS 2 — Fitur Penting (Sebagian Selesai)

### 2.1 Laporan dan Statistik ✅ (Parsial)

**Status**: `GET /laporan` sudah ada (LaporanController, statistik.blade.php, AlumniExport). Export alumni ke Excel sudah berfungsi. Yang belum ada:

**Laporan yang masih perlu ditambahkan**:

#### a. Rekap Statistik Siswa ✅ (sudah ada di `/laporan`)
- Jumlah siswa per tingkat kelas
- Jumlah siswa per rombel
- Distribusi jenis kelamin per tingkat
- Siswa masuk vs keluar per tahun pelajaran
- Tren jumlah siswa 5 tahun terakhir (grafik)

**File baru**: `app/Http/Controllers/LaporanController.php`  
**View**: `resources/views/laporan/statistik.blade.php`  
**Route**: `GET /laporan/statistik`

#### b. Laporan Prestasi Belajar ❌ (belum ada)
- Rekapitulasi nilai per kelas/semester
- Peringkat siswa dalam satu rombel
- Rata-rata nilai per mata pelajaran
- Siswa dengan nilai di bawah KKM (Kriteria Ketuntasan Minimal)

**Route**: `GET /laporan/prestasi`

#### c. Laporan Kelulusan & Alumni ❌ (belum ada halaman khusus, export Excel sudah via POST `/laporan/alumni/export`)
- Daftar alumni per tahun lulus dengan filter
- Persentase kelulusan per tahun

**Route**: `GET /laporan/alumni`

#### d. Export Laporan ke Excel/PDF
Semua laporan di atas harus bisa diexport ke Excel (via Laravel Excel) dan PDF (via DomPDF).

---

### 2.2 Cetak Dokumen Tambahan ✅ (Parsial)

**Status**: CetakController sudah ada. Yang sudah selesai:

#### a. Surat Keterangan Aktif Sekolah ✅
- Route: `GET /cetak/surat-aktif/{siswa}` (`?preview=1` untuk HTML)
- File: `resources/views/cetak/surat-aktif.blade.php`

#### b. Surat Keterangan Lulus ✅
- Route: `GET /cetak/surat-lulus/{nisn}`
- File: `resources/views/cetak/surat-lulus.blade.php`

#### c. Rekap Nilai Kelas (Leger) ✅
- Route: `GET /cetak/leger/{rombelId}?semester=1|2`
- File: `resources/views/cetak/leger.blade.php` (landscape A4)

#### d. Daftar Hadir Kelas (Absensi Template) ❌ (belum ada)
- Route: `GET /rombels/{id}/template-absensi`
- Output: Excel template dengan nama siswa per rombel

---

### 2.3 Manajemen Beasiswa Siswa

**Masalah**: Model `BeasiswaSiswa` dan tabel `beasiswa_siswas` sudah ada di database, tapi tidak ada UI sama sekali untuk mengelolanya (tidak ada routes, controller, atau views).

**Yang perlu dibuat**:
- Form tambah beasiswa di dalam halaman edit siswa atau buku induk (accordion/section)
- List riwayat beasiswa di halaman show siswa
- Tidak perlu halaman terpisah — cukup sebagai section di halaman yang ada

**Fields**: jenis_beasiswa (PIP/KIP/Daerah/Swasta/dll), keterangan, tahun_pelajaran

---

### 2.4 Manajemen Data Registrasi Siswa

**Masalah**: Model `RegistrasiSiswa` dan tabel `registrasi_siswas` sudah ada tapi tidak ada UI. Data registrasi adalah catatan historis penerimaan siswa.

**Yang perlu dibuat**:
- Section "Data Registrasi" di halaman buku induk (show dan edit)
- Field: nomor registrasi, tanggal daftar, jalur masuk (PPDB/mutasi/dll), asal sekolah

---

### 2.5 Pencarian Global (Global Search) ✅

**Status**: ✅ Selesai. `SearchController` sudah berjalan di `GET /api/search`, search box dengan Alpine.js (300ms debounce) sudah ada di header `layouts/app.blade.php`. Mencari nama/NISN/NIPD lintas semua tahun pelajaran.

---

### 2.6 Halaman Alumni yang Lebih Lengkap

**Masalah**: Halaman `alumni/index.blade.php` ada tapi sangat minimal. Hanya menampilkan list siswa dengan status "Lulus".

**Yang perlu ditambahkan**:
- Filter: tahun lulus, rombel asal
- Tampilkan: no ijazah, tanggal lulus, sekolah/kampus lanjutan (`lanjut_ke`)
- Aksi: link ke buku induk alumni
- Export daftar alumni ke Excel

---

## PRIORITAS 3 — Peningkatan Kualitas & UX (Sebagian Selesai)

### 3.1 Notifikasi & Feedback yang Lebih Baik ❌

Saat ini toast notification sudah ada (Alpine.js). Yang perlu ditingkatkan:

- **Konfirmasi sebelum aksi destruktif**: Hapus siswa, hapus prestasi, promosi massal — tampilkan modal konfirmasi dengan detail (berapa siswa yang akan diproses)
- **Progress indicator**: Saat import Excel dengan data banyak, tampilkan loading progress
- **Validasi real-time**: Form edit buku induk terlalu panjang (105KB view) — tambahkan validasi field satu per satu tanpa harus submit dulu

---

### 3.2 Optimasi Query (Performa) ✅ (Parsial)

**Status**: SiswaController sudah ditambahkan eager loading (`with('rombel')` di index, `load([...])` di show). Yang masih perlu dicek:

- `BukuIndukController::index()` — kemungkinan loop melalui buku induk dan mengakses relasi siswa satu per satu
- `RombelController::show()` — mungkin load siswa tanpa eager loading buku induk mereka

**Solusi**: Tambahkan `with()` untuk eager loading di semua query yang berkaitan:

```php
// Contoh di BukuIndukController::index()
BukuInduk::with(['siswa.rombel', 'siswa.tahunPelajaran'])
    ->withCount('prestasiBelajar')
    ->paginate(25);
```

**Tools**: Gunakan Laravel Debugbar (dev dependency) untuk identifikasi N+1 query.

---

### 3.3 Perbaikan Form Edit Buku Induk

File `resources/views/buku-induk/edit.blade.php` berukuran 105KB — sangat besar dan kemungkinan berat di browser.

**Saran refactor**:
- Pecah form menjadi tab atau accordion yang di-load lazy (bukan semua sekaligus)
- Gunakan Livewire atau Alpine.js untuk partial update tanpa full page reload
- Atau minimal validasi per-section sebelum lanjut ke section berikutnya (wizard form)

---

### 3.4 Perbaikan Halaman Dashboard ✅ (Parsial)

**Status**: Dashboard sudah ditambahkan distribusi siswa per tingkat (tabel L/P/total + progress bar) dan warning badges (rombel tanpa anggota, buku induk tanpa foto). Yang masih belum ada:

- **Grafik trend** jumlah siswa per tahun (Chart.js) ❌
- **Widget quick actions**: Import siswa, Tambah rombel ❌ (Quick action tahun pelajaran sudah ada)

---

### 3.5 Konfigurasi Sekolah (Settings)

Halaman `/settings` sudah ada tapi kemungkinan belum dimanfaatkan penuh. Yang perlu dikonfigurasi dari UI:

- **Nama sekolah, NPSN, alamat, kota** — untuk header cetak buku induk dan surat
- **Logo sekolah** — ditampilkan di header cetak dan sidebar
- **KKM per mata pelajaran** — untuk highlight nilai di bawah KKM
- **Kepala sekolah** — nama & NIP untuk tanda tangan cetak

**Implementasi**:
- Tabel `settings` sudah ada dengan key-value JSON
- Tambahkan field di `resources/views/settings/index.blade.php` untuk field-field di atas
- Buat helper `setting('nama_sekolah')` untuk akses mudah di views

---

### 3.6 Soft Deletes untuk Data Kritis ✅ (Parsial)

**Status**: `siswas` dan `buku_induks` sudah diberi `deleted_at` (migration 2026_04_18_100001) dan trait `SoftDeletes`. Yang masih belum ada:

- `prestasi_belajars` — belum ada soft delete ❌
- Halaman "Sampah/Arsip" untuk restore data ❌ (soft deleted records tidak bisa dipulihkan via UI)

---

### 3.7 Tambahan Fitur Import

#### a. Import BukuInduk dari Excel (Massal)
Saat ini import hanya untuk data pokok siswa. Perlu import juga untuk:
- Data buku induk lengkap (identitas tambahan, orang tua detail)
- Data prestasi belajar massal (nilai rapor dari file Excel per rombel)

**File terkait**:
- `app/Imports/` — sudah ada `PrestasiImport.php`, perlu `BukuIndukImport.php`

#### b. Validasi Import yang Lebih Detail
Saat ini error import mungkin hanya ditampilkan sebagai pesan umum. Tambahkan:
- Row-by-row validation dengan pesan error per baris
- Preview data sebelum commit import (sudah ada sebagian untuk siswa, perlu dikembangkan)
- Download file error report (baris mana yang gagal dan kenapa)

---

## PRIORITAS 4 — Fitur Tambahan Jangka Menengah (Sebagian Selesai)

### 4.1 Mutasi Siswa (Pindah Masuk / Pindah Keluar) ❌

Saat ini field `pindah_dari`, `kelas_pindah_masuk`, `tgl_pindah_masuk` sudah ada di tabel `buku_induks`, tapi tidak ada alur khusus untuk mencatat mutasi.

**Yang perlu dibuat**:
- Form "Mutasi Masuk": siswa dari sekolah lain masuk di tengah tahun
- Form "Mutasi Keluar": siswa pindah ke sekolah lain (ubah status ke "Keluar")
- Riwayat mutasi di halaman buku induk

---

### 4.2 Penilaian Sikap dan Kehadiran yang Lebih Terstruktur

Saat ini field `sikap`, `kerajinan`, `kebersihan_kerapian` dan `hadir_sakit/izin/alpha` ada di `prestasi_belajars` tapi mungkin inputnya belum terstruktur.

**Yang perlu ditingkatkan**:
- Dropdown dengan pilihan nilai sikap standar (Sangat Baik/Baik/Cukup/Perlu Bimbingan)
- Validasi bahwa total hari hadir + sakit + izin + alpha = total hari efektif sekolah
- Input kehadiran terintegrasi dengan data minggu efektif per semester

---

### 4.3 Wali Kelas per Rombel ✅ (Parsial)

**Status**: Kolom `nama_wali_kelas` (string) sudah ditambahkan ke `rombels` (migration 2026_04_18_100002). Nama wali kelas sudah tampil di list rombel, show rombel, dan leger cetak. Yang masih belum ada:
- Tidak ada tabel `gurus` terpisah — wali kelas hanya nama teks biasa
- Tidak ada relasi ke `users` untuk wali kelas yang login ke sistem

---

### 4.4 Notifikasi Email

Saat ini tidak ada sistem email sama sekali.

**Yang bisa ditambahkan**:
- Email konfirmasi saat user baru dibuat (Laravel `Mail`)
- Notifikasi ke admin saat import data selesai (terutama untuk import besar via queue)
- Reset password via email (form sudah ada tapi belum jelas apakah email dikonfigurasi)

**Konfigurasi** di `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

---

### 4.5 API untuk Integrasi Eksternal

Saat ini `routes/api.php` kosong. Jika ada rencana integrasi dengan aplikasi lain (e-Raport, PPDB Online, aplikasi mobile):

**Endpoint yang disarankan**:
```
GET  /api/v1/siswas           → list siswa (paginated, filtered)
GET  /api/v1/siswas/{nisn}    → detail siswa
GET  /api/v1/buku-induk/{nisn} → buku induk siswa
GET  /api/v1/rombels           → list rombel aktif
POST /api/v1/siswas            → tambah siswa (untuk integrasi PPDB)
```

**Auth**: Gunakan Laravel Sanctum (sudah terinstal via default Laravel) dengan API token.

---

## Panduan untuk Agent/Developer Selanjutnya

### Urutan Pengerjaan yang Disarankan

```
Selesai (18 April 2026):
  ✅ 1.1 Artisan command GenerateBukuInduk
  ✅ 1.2 Upload foto di buku induk
  ✅ 1.3 Link navigasi silang antar halaman
  ✅ 1.4 Progress kelengkapan di buku induk show
  ✅ 2.1a Laporan statistik siswa (GET /laporan)
  ✅ 2.1d Export alumni ke Excel (POST /laporan/alumni/export)
  ✅ 2.2a Cetak Surat Keterangan Aktif
  ✅ 2.2b Cetak Surat Keterangan Lulus
  ✅ 2.2d Cetak Leger Nilai Kelas (PDF landscape)
  ✅ 2.5 Global Search (header + API)
  ✅ 3.2 Eager loading di SiswaController
  ✅ 3.4 Dashboard: distribusi tingkat + warning badges
  ✅ 3.6 Soft Deletes (siswas + buku_induks)
  ✅ 4.3 Wali Kelas per Rombel (nama_wali_kelas)

Selesai (19 April 2026 — sesi kedua):
  ✅ 2.1b Laporan Prestasi Belajar (`GET /laporan/prestasi`)
  ✅ 2.1c Laporan Alumni halaman (`GET /laporan/alumni`)
  ✅ 2.2c Template Absensi Excel (`GET /cetak/template-absensi/{rombelId}`, AbsensiTemplateExport)
  ✅ 2.3 UI Beasiswa Siswa (BeasiswaController, sections in buku-induk/show)
  ✅ 2.4 UI Registrasi Siswa (RegistrasiController, sections in buku-induk/show)
  ✅ 2.6 Halaman Alumni Lengkap (no_ijazah, tgl_lulus, lanjut_ke columns added)
  ✅ 3.4 Grafik trend Chart.js di dashboard (line chart menggunakan $trendPerTahun)
  ✅ 3.6 Halaman "Sampah" untuk restore soft-deleted (TrashController, `/trash`)
  ✅ Sidebar Laporan dropdown (3 sub-items) + Sampah link under Arsip Siswa

Masih belum dikerjakan:
  ❌ 3.1 Konfirmasi destruktif + progress indicator import
  ❌ 3.3 Refactor form buku induk edit (tab/accordion) — file 105KB, berisiko
  ❌ 3.7 Import BukuInduk massal + error report
  ❌ 4.1 Mutasi Siswa (pindah masuk/keluar) — workflow kompleks
  ❌ 4.2 Penilaian Sikap & Kehadiran terstruktur (dropdown Sangat Baik/Baik/dll)
  ❌ 4.4 Notifikasi Email — butuh konfigurasi .env SMTP
  ❌ 4.5 REST API untuk integrasi eksternal (butuh Sanctum setup)
```

### Perintah Berguna

```bash
# Jalankan development server
php artisan serve

# Jalankan queue worker (untuk export async)
php artisan queue:work --queue=default

# Generate buku induk untuk siswa existing (setelah dibuat)
php artisan buku-induk:generate

# Lihat semua routes
php artisan route:list

# Fresh migration + seeder
php artisan migrate:fresh --seed

# Build assets frontend
npm run dev       # development
npm run build     # production
```

### Konvensi Kode yang Sudah Dipakai

- **UUID** sebagai primary key di semua tabel (gunakan `Str::uuid()` atau `HasUuids` trait)
- **Spatie Activity Log custom**: Gunakan `ActivityLogService` (bukan Spatie Activity Log package) — lihat `app/Services/ActivityLogService.php`
- **Role check di controller**: Gunakan `$this->middleware('role:Super Admin|Operator')` atau `auth()->user()->hasRole(...)`
- **Global scope**: `Siswa` otomatis difilter ke tahun pelajaran aktif — gunakan `Siswa::withoutGlobalScope('tahun_aktif')` jika perlu semua data
- **Response**: Gunakan `redirect()->back()->with('success', '...')` untuk flash message
- **Views**: Komponen toast notification sudah tersedia, trigger dengan session flash `success`, `error`, `warning`
- **Form method spoofing**: Gunakan `@method('PUT')` dan `@method('DELETE')` di form HTML

### File Referensi Penting

| Tujuan | File |
|--------|------|
| Pola controller lengkap | `app/Http/Controllers/SiswaController.php` |
| Pola upload file/avatar | `app/Http/Controllers/ProfileController.php` |
| Pola import Excel | `app/Imports/SiswaImport.php` |
| Pola export Excel | `app/Exports/ActivityExport.php` |
| Pola async job | `app/Jobs/ProcessBukuIndukExport.php` |
| Pola audit log | `app/Services/ActivityLogService.php` |
| Layout utama (sidebar, menu) | `resources/views/layouts/app.blade.php` |
| Contoh view kompleks | `resources/views/buku-induk/show.blade.php` |
| Contoh view cetak | `resources/views/buku-induk/print.blade.php` |

---

## Catatan Teknis Tambahan

### Database Notes
- Tabel `registrasi_siswas` ada tapi fieldnya belum jelas — perlu cek migration dan tentukan field apa yang relevan sebelum buat UI
- Field `guru_id` di `rombels` bertipe string/uuid tapi tidak ada FK constraint ke tabel mana pun — perlu diputuskan apakah guru adalah User atau tabel terpisah
- `prestasi_nilais` dan `prestasi_belajars` — ada dua sistem pencatatan nilai (nilai per kolom di `prestasi_belajars` DAN via tabel pivot `prestasi_nilais`) — perlu disinkronkan penggunaannya agar tidak redundan

### Keamanan
- Pastikan semua route yang memerlukan autentikasi sudah dilindungi middleware `auth`
- Export file (ZIP, PDF) harus diverifikasi bahwa hanya pemilik job yang bisa download
- Upload foto — validasi tipe file dan ukuran di server (jangan hanya di client)
- Input siswa — pastikan validasi NISN (10 digit angka) dan NIPD dilakukan sebelum save

### Performa
- Halaman `siswas/index.blade.php` (63KB) dan `buku-induk/edit.blade.php` (105KB) — ukuran view yang besar bisa memperlambat Blade compilation. Pertimbangkan memecah menjadi partial views dengan `@include`
- Gunakan `paginate(25)` konsisten — jangan `get()` untuk data banyak
- Index database: pastikan kolom yang sering di-filter (nisn, status, tahun_pelajaran_id, rombel_id) sudah terindex

---

*Dokumen ini dibuat berdasarkan analisa codebase pada 18 April 2026 oleh Claude Code. Terakhir diperbarui 18 April 2026 — mencerminkan status setelah implementasi Prioritas 1–4 parsial.*
