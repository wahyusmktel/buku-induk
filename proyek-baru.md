# Rincian Pekerjaan: Pembaruan Master Import Buku Induk & Restrukturisasi Database

## Tujuan
Memperbarui format import data "Master Buku Induk" pada halaman Data Pokok Siswa sesuai urutan baru (35 kolom) untuk memudahkan proses import secara masal, dan melakukan pemecahan tabel-tabel terkait informasi detail siswa untuk struktur data yang lebih spesifik dan *normalized*.

## Rincian Kolom Import Baru (Berurutan)
1. **Nomor** (Abaikan, tidak masuk ke database, khusus untuk urutan di Excel)
2. **NIS** (Tabel `siswas`)
3. **NISN** (Tabel `siswas`)
4. **NIK** (Tabel `siswas`)
5. **Nama Lengkap Siswa** (Tabel `siswas`)
6. **Nama Panggilan** (Tabel `siswas`)
7. **Jenis Kelamin** (Tabel `siswas`)
8. **Tempat Kelahiran** (Tabel `siswas`)
9. **Tanggal Lahir** (Tabel `siswas`)
10. **Agama** (Tabel `siswas`)
11. **Kewarganegaraan** (Tabel `siswas`)
12. **Jumlah Saudara Kandung** (Tabel Baru: `data_periodik_siswas`)
13. **Jumlah Saudara Tiri** (Tabel Baru: `data_periodik_siswas`)
14. **Jumlah Saudara Angkat** (Tabel Baru: `data_periodik_siswas`)
15. **Bahasa Sehari-hari** (Tabel Baru: `data_periodik_siswas`)
16. **Berat Badan** (Tabel Baru: `keadaan_jasmani_siswas`)
17. **Tinggi Badan** (Tabel Baru: `keadaan_jasmani_siswas`)
18. **Golongan Darah** (Tabel Baru: `keadaan_jasmani_siswas`)
19. **Nama Riwayat Penyakit** (Tabel Baru: `keadaan_jasmani_siswas`)
20. **Kelainan Jasmani Siswa** (Tabel Baru: `keadaan_jasmani_siswas`)
21. **Nomor Telepon** (Tabel `siswas`)
22. **Alamat Tinggal** (Tabel Baru: `data_periodik_siswas`)
23. **Bertempat Tinggal Pada** (Tabel Baru: `data_periodik_siswas`)
24. **Jarak Tempat Tinggal ke Sekolah** (Tabel Baru: `data_periodik_siswas`)
25. **Nama Ayah** (Tabel Baru: `data_orang_tua_siswas`)
26. **Pendidikan Terakhir Ayah** (Tabel Baru: `data_orang_tua_siswas`)
27. **Pekerjaan Ayah** (Tabel Baru: `data_orang_tua_siswas`)
28. **Nama Ibu** (Tabel Baru: `data_orang_tua_siswas`)
29. **Pendidikan Terakhir Ibu** (Tabel Baru: `data_orang_tua_siswas`)
30. **Pekerjaan Ibu** (Tabel Baru: `data_orang_tua_siswas`)
31. **Nama Wali** (Tabel Baru: `data_orang_tua_siswas`)
32. **Status Hubungan Keluarga dengan Wali** (Tabel Baru: `data_orang_tua_siswas`)
33. **Pendidikan Terakhir Wali** (Tabel Baru: `data_orang_tua_siswas`)
34. **Pekerjaan Wali** (Tabel Baru: `data_orang_tua_siswas`)
35. **Tingkat Siswa Saat Ini** (Tingkat Kelas - Tabel `siswas` / `rombels`)

## Tabel Tambahan (Diluar Import Excel)
Kolom-kolom ini akan diisi secara manual melalui aplikasi (Halaman Buku Induk):
1. **Tabel `beasiswa_siswas`**
   - Riwayat informasi beasiswa siswa.
2. **Tabel `registrasi_siswas`**
   - Tamat belajar (tahun, melanjutkan ke sekolah)
   - Pindah sekolah (dari sekolah, ke sekolah, ke kelas, tanggal)
   - Keluar sekolah (tanggal, alasan, tanggal keluar)
   - Lain-lain (catatan yang penting)

## Rencana Teknis
1. **Migrations:** Membuat file migration baru untuk masing-masing tabel tersebut dengan relasi foreign key `siswa_id` pada tabel terpusat `siswas`. Relasi ini menggunakan tipe data UUID yang cocok dengan *primary key* `id` di `siswas`.
2. **Models:** Membuat representasi model di Laravel (`DataPeriodikSiswa`, `KeadaanJasmaniSiswa`, `DataOrangTuaSiswa`, `BeasiswaSiswa`, `RegistrasiSiswa`). Menambahkan metode relasi di model `Siswa` (seperti `hasOne` / `hasMany`).
3. **Import Logic (`MasterBukuIndukImport.php`):** Mengubah indeks pembacaan baris Excel dari `0` sampai `34`.
  - Array indeks 0 diabaikan.
  - Untuk data yang berada dalam tabel baru, gunakan fitur `updateOrCreate` terkait `siswa_id` agar dapat diproses dengan baik dan terikat relasi.

> **Catatan Pengingat:** Pekerjaan ini menunggu review manual dan persetujuan eksekusi untuk hasil akhir.
