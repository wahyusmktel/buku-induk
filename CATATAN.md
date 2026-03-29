# 📓 Catatan Lanjutan Pengembangan — Buku Induk Digital
> Dibuat: 29 Maret 2026 | Terakhir diperbarui oleh: Antigravity Agent

---

## ✅ Yang Sudah Selesai Hari Ini

### 1. Perbaikan Kolom Status di Data Pokok Siswa
- Kolom **Status** di tabel `siswas/index.blade.php` sudah diperbaiki (sebelumnya hilang/missing)
- Badge status warna-warni sudah ditambahkan: hijau (Aktif), biru (Lulus), merah (Keluar/Mutasi)
- Status juga tampil di halaman detail (`show.blade.php`) dan bisa diedit di (`edit.blade.php`)
- `SiswaController::update` sudah diperbarui untuk validasi & simpan kolom `status` dan `rombel_saat_ini`

### 2. Filter Rombongan Belajar by Status
- `RombelController::index` → hanya tampilkan rombel yang punya siswa **Aktif**
- `RombelController::show` → daftar anggota rombel hanya berisi siswa **Aktif**
- Siswa Lulus/Keluar otomatis tidak tampil di rombel

### 3. README.md
- Dibuat `README.md` lengkap dalam Bahasa Inggris
- Dibuat oleh **@wahyusmktel**
- Berisi: tech stack, fitur, cara instalasi, cara dev

### 4. Fitur Buku Induk (FITUR UTAMA HARI INI — FRESH)
Fitur baru yang baru saja dibangun, **belum sempat dicoba/ditest**:

#### Database (sudah di-migrate ✅)
- `buku_induks` — tabel utama buku induk (linked ke NISN siswa, bukan per tahun)
- `prestasi_belajars` — nilai rapor per kelas (1-6) × semester (1-2)
- Tambahan kolom di `siswas`: `nama_panggilan`, `kewarganegaraan`, `bahasa_sehari_hari`, `golongan_darah`, `riwayat_penyakit`

#### Model
- `app/Models/BukuInduk.php` — dengan accessor `kelengkapan` (persen)
- `app/Models/PrestasiBelajar.php` — auto-hitung jumlah & rata-rata nilai saat save

#### Controller
- `app/Http/Controllers/BukuIndukController.php` — index, show, edit, update, print
- `app/Http/Controllers/PrestasiController.php` — store (upsert), destroy

#### Routes (di `routes/web.php`)
```
GET  /buku-induk              → buku-induk.index
GET  /buku-induk/{nisn}       → buku-induk.show
GET  /buku-induk/{nisn}/print → buku-induk.print
GET  /buku-induk/{nisn}/edit  → buku-induk.edit  [role: SA|Operator|TU]
PUT  /buku-induk/{nisn}       → buku-induk.update [role: SA|Operator|TU]
POST /buku-induk/{nisn}/prestasi        → prestasi.store
DELETE /buku-induk/{nisn}/prestasi/{id} → prestasi.destroy
```

#### Views (di `resources/views/buku-induk/`)
- `index.blade.php` — grid siswa, search, progress kelengkapan
- `show.blade.php` — 4 tab: Identitas, Orang Tua, Prestasi Akademik, Riwayat Sekolah
- `edit.blade.php` — 3 tab: Identitas Tambahan, Orang Tua Lengkap, Masuk/Keluar
- `print.blade.php` — format cetak resmi A4 (mirip format buku induk SD)

#### Sidebar
- Menu **"Buku Induk"** sudah ditambahkan ke sidebar (di bawah Rombongan Belajar)

---

## ⚠️ Yang Perlu Dilakukan Besok (Prioritas)

### Tinggi 🔴
- [ ] **Test fitur Buku Induk end-to-end** — buka `/buku-induk`, coba view, edit, cetak
- [ ] **Test input Prestasi Nilai** — coba modal "Tambah / Update Nilai" di tab Prestasi Akademik
- [ ] Pastikan siswa existing (yang sudah ada di DB) sudah auto-punya record `buku_induks` → perlu **seeder/command** karena hook `created` hanya untuk siswa baru
- [ ] Cek apakah route `rombels.*` masih accessible (sebelumnya hanya di group `role:...`, perlu dicek)

### Sedang 🟡
- [ ] **Buat command/seeder** untuk generate `BukuInduk` untuk semua siswa existing yang punya NISN tapi belum ada recordnya di `buku_induks`
- [ ] Tambahkan link ke Buku Induk dari halaman Detail Siswa (`siswas/show.blade.php`) — tombol "Lihat Buku Induk"
- [ ] Tambahkan link ke Buku Induk dari halaman Rombel (`rombels/show.blade.php`) — tombol di kolom aksi

### Rendah 🟢
- [ ] Export PDF menggunakan library DomPDF (`barryvdh/laravel-dompdf`) sebagai alternatif print browser
- [ ] Tampilkan kelengkapan Buku Induk (%) di halaman detail siswa
- [ ] Halaman pencarian global (cari siswa + buku induk sekaligus)

---

## 🐛 Potensi Bug / Hal yang Perlu Dicek

1. **Siswa tanpa NISN** — `buku_induks` terhubung via NISN. Siswa tanpa NISN tidak akan punya buku induk. Perlu keputusan: apakah bisa tetap dibuat manual?

2. **RombelController** — setelah perbaikan filter aktif, akses route `/rombels` sekarang dibatasi ke role `Super Admin|Operator|Tata Usaha`. Pastikan tidak ada user lain yang butuh akses ini.

3. **Multiple siswa dengan NISN sama** — saat import Dapodik dari tahun ke tahun, siswa yang sama bisa punya **banyak record** di tabel `siswas` (per tahun pelajaran). `BukuInduk` hanya satu per NISN. Controller sudah handle ini dengan `orderBy('created_at', 'desc')->first()`.

4. **Print view** — menggunakan `window.print()` browser. Jika sekolah butuh PDF langsung tersimpan, perlu DomPDF.

---

## 🗂️ Arsitektur Ringkas

```
siswas (per tahun pelajaran)
│  ├── tahun_pelajaran_id
│  ├── nisn (key pemersatu)
│  └── status: Aktif | Lulus | Keluar/Mutasi
│
buku_induks (permanen, 1 per siswa)
│  ├── nisn (FK ke siswa)
│  ├── no_induk (nomor induk sekolah)
│  ├── data identitas tambahan
│  ├── data orang tua lengkap
│  └── data masuk/keluar/lulus
│
prestasi_belajars (1 per semester per buku induk)
   ├── buku_induk_id
   ├── kelas (1-6)
   ├── semester (1-2)
   ├── nilai per mata pelajaran
   ├── kehadiran (sakit/izin/alpha)
   └── kepribadian & kenaikan kelas
```

---

## 📁 File-file Penting

| File | Keterangan |
|---|---|
| `app/Models/BukuInduk.php` | Model utama buku induk |
| `app/Models/PrestasiBelajar.php` | Model nilai per semester |
| `app/Models/Siswa.php` | Model siswa (ada hook auto-create buku induk) |
| `app/Http/Controllers/BukuIndukController.php` | Controller utama |
| `app/Http/Controllers/PrestasiController.php` | Controller nilai |
| `resources/views/buku-induk/` | Semua view buku induk |
| `routes/web.php` | Semua route terdaftar di sini |
| `database/migrations/2026_03_29_163057_*` | Migration buku_induks |
| `database/migrations/2026_03_29_163103_*` | Migration prestasi_belajars |

---

## 💬 Pertanyaan Terbuka (untuk diputuskan)

1. Apakah nilai rapor hanya manual, atau akan ada import dari aplikasi e-Raport?
2. Apakah `no_induk` (nomor buku induk) diisi otomatis (increment) atau manual oleh TU?
3. Apakah perlu halaman **Dashboard** yang menampilkan statistik (jumlah siswa aktif, lulus, kelengkapan buku induk rata-rata, dll)?

---

*Catatan ini dibuat otomatis oleh Antigravity Agent. Lanjut kapan saja dengan membuka catatan ini sebagai konteks.*
