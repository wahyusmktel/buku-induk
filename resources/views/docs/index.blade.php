@extends('layouts.app')

@section('title', 'Dokumentasi Penggunaan')
@section('header_title', 'Dokumentasi Penggunaan')

@section('breadcrumb')
    Dokumentasi
@endsection

@section('content')
<div class="max-w-4xl mx-auto" id="docs-content">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8 print:shadow-none print:border-0">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-8 py-10 text-white">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-indigo-200 text-sm font-semibold uppercase tracking-widest">Panduan Pengguna</span>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Panduan Penggunaan Aplikasi</h1>
                    <p class="text-indigo-100 text-base max-w-xl leading-relaxed">
                        Panduan langkah demi langkah dalam mengakses dan mengelola Sistem Informasi Buku Induk SD Muhammadiyah Gisting.
                    </p>
                </div>
                <button onclick="window.print()"
                        class="print:hidden flex-shrink-0 flex items-center gap-2 px-5 py-2.5 bg-white/15 hover:bg-white/25 border border-white/30 rounded-xl text-white font-semibold text-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>

        {{-- Table of Contents --}}
        <div class="px-8 py-5 bg-indigo-50/60 border-b border-indigo-100">
            <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">Daftar Isi</p>
            <ol class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                @php
                $toc = [
                    ['1', 'langkah-1', 'Mengakses Halaman Utama (Landing Page)'],
                    ['2', 'langkah-2', 'Masuk ke Sistem (Login)'],
                    ['3', 'langkah-3', 'Mengenal Dashboard'],
                    ['4', 'langkah-4', 'Mengatur Tahun Pelajaran'],
                    ['5', 'langkah-5', 'Pengaturan Dokumen Buku Induk'],
                    ['6', 'langkah-6', 'Pengaturan Tampilan Laman'],
                    ['7', 'langkah-7', 'Kelola Data Referensi Mata Pelajaran'],
                    ['8', 'langkah-8', 'Kelola Data Referensi Ekstrakurikuler'],
                    ['9', 'langkah-9', 'Kelola Data Pokok Siswa'],
                    ['10', 'langkah-10', 'Kelola Rombongan Belajar'],
                    ['11', 'langkah-11', 'Cetak & Manajemen Buku Induk'],
                    ['12', 'langkah-12', 'Data Alumni (Kelulusan)'],
                    ['13', 'langkah-13', 'Arsip & Pemulihan Data'],
                ];
                @endphp
                @foreach($toc as $item)
                <li>
                    <a href="#{{ $item[1] }}" class="flex items-center gap-2 text-sm font-semibold text-indigo-700 hover:text-indigo-900 transition-colors group print:text-black">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600 flex-shrink-0 group-hover:bg-indigo-200 transition-colors">{{ $item[0] }}</span>
                        {{ $item[2] }}
                    </a>
                </li>
                @endforeach
            </ol>
        </div>
    </div>

    {{-- Langkah 1: Landing Page --}}
    <div id="langkah-1" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">

        {{-- Step Header --}}
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-indigo-600">1</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Mengakses Halaman Utama</h2>
                <p class="text-slate-500 text-sm mt-0.5">Tampilan awal saat pertama kali membuka aplikasi</p>
            </div>
        </div>

        <div class="px-8 py-6 space-y-6">

            {{-- Penjelasan --}}
            <div class="prose prose-slate max-w-none">
                <p class="text-slate-600 leading-relaxed">
                    Ketika pertama kali membuka aplikasi Buku Induk melalui browser, Anda akan diarahkan ke <strong>Halaman Utama (Landing Page)</strong>.
                    Halaman ini merupakan pintu masuk utama ke sistem dan menampilkan informasi umum mengenai aplikasi.
                </p>
            </div>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Utama</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- Uncomment baris di bawah ini setelah screenshot tersedia --}}
                {{-- <img src="{{ asset('images/docs/landing-page.png') }}" alt="Halaman Utama Buku Induk" class="w-full h-auto"> --}}
            </div>

            {{-- Info Points --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Informasi Sekolah</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Halaman ini menampilkan profil singkat SD Muhammadiyah Gisting dan fitur-fitur utama aplikasi.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Navigasi Tersedia</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Terdapat menu navigasi: <strong>Beranda</strong>, <strong>Tentang</strong>, <strong>Kontak</strong>, dan tombol <strong>Login</strong>.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Akses Langsung</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Klik tombol <strong class="text-amber-600">Login</strong> berwarna kuning di pojok kanan atas untuk masuk ke sistem.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2v-2a9 9 0 10-18 0v2a2 2 0 002 2h4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Sudah Login?</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Jika sebelumnya sudah login, tombol akan berubah menjadi <strong class="text-amber-600">Dashboard</strong> dan langsung mengarah ke dalam sistem.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Callout --}}
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-amber-800 leading-relaxed">
                    <strong>Catatan:</strong> Pastikan Anda mengakses aplikasi menggunakan browser modern seperti Google Chrome, Mozilla Firefox, atau Microsoft Edge untuk pengalaman terbaik.
                </p>
            </div>
        </div>
    </div>

    {{-- Langkah 2: Login --}}
    <div id="langkah-2" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">

        {{-- Step Header --}}
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-emerald-600">2</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Masuk ke Sistem</h2>
                <p class="text-slate-500 text-sm mt-0.5">Gunakan akun yang telah terdaftar di sistem</p>
            </div>
        </div>

        <div class="px-8 py-6 space-y-6">

            {{-- Penjelasan --}}
            <div>
                <p class="text-slate-600 leading-relaxed">
                    Setelah mengeklik tombol <strong>Masuk</strong> di halaman utama, Anda akan diarahkan ke <strong>Halaman Masuk</strong>.
                    Di sini Anda perlu memasukkan kredensial (nama pengguna dan kata sandi) yang telah diberikan oleh Administrator sekolah.
                </p>
            </div>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Login</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- Uncomment baris di bawah ini setelah screenshot tersedia --}}
                {{-- <img src="{{ asset('images/docs/login-page.png') }}" alt="Halaman Login Buku Induk" class="w-full h-auto"> --}}
            </div>

            {{-- Form Fields --}}
            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Kolom yang perlu diisi:</p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">Alamat Email / NIP</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Masukkan alamat email yang terdaftar di sistem, atau NIP jika menggunakan login berbasis NIP. Contoh: <code class="bg-slate-100 px-1.5 py-0.5 rounded text-indigo-600 font-mono text-xs">guru@sdmuhgisting.sch.id</code></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">Kata Sandi</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Masukkan kata sandi akun Anda. Kata sandi bersifat rahasia dan <strong>tidak boleh dibagikan</strong> kepada siapapun.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">Ingat Saya <span class="text-xs font-normal text-slate-400">(Opsional)</span></p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Centang opsi ini agar sesi login Anda tersimpan lebih lama. Tidak disarankan pada perangkat komputer publik atau bersama.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Langkah Login --}}
            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Langkah-langkah login:</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Buka halaman utama aplikasi di browser Anda.', 'sky'],
                        ['Klik tombol <strong class="text-amber-600">Login</strong> yang berwarna kuning di pojok kanan atas halaman.', 'amber'],
                        ['Masukkan <strong>Alamat Email</strong> atau <strong>NIP</strong> Anda pada kolom pertama.', 'indigo'],
                        ['Masukkan <strong>Kata Sandi</strong> Anda pada kolom kedua.', 'indigo'],
                        ['Klik tombol <strong class="text-sky-600">Masuk Sistem</strong> berwarna biru.', 'emerald'],
                        ['Jika berhasil, Anda akan otomatis diarahkan ke halaman <strong>Dashboard</strong>.', 'emerald'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Lupa Password --}}
            <div class="rounded-xl border border-slate-200 divide-y divide-slate-100 overflow-hidden">
                <div class="flex items-start gap-3 px-5 py-4 bg-rose-50/50">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-rose-700 mb-0.5">Login Gagal?</p>
                        <p class="text-xs text-rose-600 leading-relaxed">Pastikan email/NIP dan kata sandi yang Anda masukkan sudah benar. Perhatikan huruf kapital pada kata sandi (bersifat <em>case-sensitive</em>).</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 px-5 py-4">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-slate-700 mb-0.5">Lupa Kata Sandi?</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Klik tautan <strong>Lupa Kata Sandi?</strong> pada halaman login, lalu ikuti instruksi yang muncul. Atau hubungi Administrator sekolah untuk mereset kata sandi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Langkah 3: Dashboard --}}
    <div id="langkah-3" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-violet-600">3</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Mengenal Dasbor</h2>
                <p class="text-slate-500 text-sm mt-0.5">Halaman ringkasan informasi sistem</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Setelah berhasil masuk, Anda akan diarahkan ke halaman <strong>Dasbor</strong>. Halaman ini menampilkan ringkasan data dan kondisi sistem secara menyeluruh sehingga Anda dapat langsung memantau keadaan terkini.
            </p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Dashboard</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/dashboard.png') }}" alt="Dashboard" class="w-full h-auto"> --}}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-sky-100 bg-sky-50/60 p-4">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700 mb-1">Statistik Siswa</p>
                    <p class="text-xs text-slate-500 leading-relaxed">Menampilkan jumlah <strong>Siswa Aktif</strong>, <strong>Alumni</strong>, dan <strong>Rombongan Belajar</strong> secara real-time.</p>
                </div>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700 mb-1">Grafik Tren</p>
                    <p class="text-xs text-slate-500 leading-relaxed">Grafik garis menampilkan tren jumlah siswa aktif dan alumni per tahun pelajaran.</p>
                </div>
                <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700 mb-1">Aksi Cepat</p>
                    <p class="text-xs text-slate-500 leading-relaxed">Admin dapat langsung mengganti <strong>Tahun Pelajaran aktif</strong> melalui widget di pojok kanan dashboard.</p>
                </div>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Informasi yang ditampilkan di Dashboard:</p>
                <ul class="space-y-2">
                    @foreach([
                        ['Kartu ringkasan:', 'Siswa Aktif, jumlah Alumni, dan total Rombongan Belajar pada tahun pelajaran aktif.'],
                        ['Peringatan sistem:', 'Muncul jika ada rombel tanpa anggota atau buku induk yang belum lengkap fotonya.'],
                        ['Tabel distribusi:', 'Persebaran siswa per tingkat kelas beserta proporsi laki-laki dan perempuan.'],
                        ['Grafik tren:', 'Perkembangan jumlah siswa aktif vs alumni dari tahun ke tahun.'],
                        ['Tombol pintasan:', '"Data Siswa" dan "Buku Induk" untuk akses cepat ke halaman yang paling sering digunakan.'],
                    ] as $row)
                    <li class="flex items-start gap-2.5 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                        <span><strong>{{ $row[0] }}</strong> {{ $row[1] }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex items-start gap-3 bg-sky-50 border border-sky-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-sky-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-sky-800 leading-relaxed"><strong>Tips:</strong> Widget <em>Aksi Cepat</em> untuk mengganti tahun pelajaran aktif hanya muncul untuk pengguna dengan peran <strong>Super Admin</strong>.</p>
            </div>
        </div>
    </div>

    {{-- Langkah 4: Tahun Pelajaran --}}
    <div id="langkah-4" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-sky-600">4</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Mengatur Tahun Pelajaran</h2>
                <p class="text-slate-500 text-sm mt-0.5">Langkah wajib sebelum mulai menginput data siswa</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Tahun Pelajaran adalah fondasi dari semua data di aplikasi ini. Setiap data siswa, nilai, dan rombel terikat pada tahun pelajaran tertentu. Pastikan tahun pelajaran sudah dibuat dan <strong>diaktifkan</strong> sebelum mulai menginput data apapun.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Administrator → Tahun Pelajaran</strong> di sidebar kiri.</p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Tahun Pelajaran</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/tahun-pelajaran.png') }}" alt="Tahun Pelajaran" class="w-full h-auto"> --}}
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Cara menambah tahun pelajaran baru:</p>
                <ol class="space-y-3">
                    @foreach([
                        'Klik tombol <strong class="text-sky-600">+ Tambah Tahun Pelajaran</strong> di pojok kanan atas.',
                        'Pada form yang muncul, pilih <strong>Tahun Pelajaran</strong> dari dropdown (contoh: 2025/2026).',
                        'Pilih <strong>Semester</strong>: <em>Ganjil</em> atau <em>Genap</em>.',
                        'Klik <strong>Simpan Data</strong>. Tahun pelajaran baru akan muncul di daftar.',
                        'Klik tombol <strong class="text-emerald-600">Aktifkan</strong> pada baris tahun pelajaran yang ingin dijadikan sesi aktif.',
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-sky-100 border border-sky-200 flex items-center justify-center text-xs font-extrabold text-sky-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="rounded-xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
                <div class="flex items-start gap-3 px-5 py-4 bg-amber-50/50">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-700 mb-0.5">Fitur Salin dari Ganjil</p>
                        <p class="text-xs text-amber-600 leading-relaxed">Jika Anda membuat semester <strong>Genap</strong> dan sudah ada semester <strong>Ganjil</strong> di tahun yang sama, akan muncul tombol <strong>"Salin dari Ganjil"</strong> yang otomatis menduplikasi seluruh rombel dan siswa dari semester ganjil.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 px-5 py-4">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-slate-700 mb-0.5">Hati-hati Menghapus</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Tahun pelajaran yang sedang <strong>aktif</strong> tidak dapat dihapus. Tombol hapus hanya tersedia untuk tahun pelajaran yang berstatus <em>Non-aktif</em>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Langkah 5: Pengaturan Dokumen --}}
    <div id="langkah-5" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-amber-600">5</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pengaturan Dokumen Buku Induk</h2>
                <p class="text-slate-500 text-sm mt-0.5">Konfigurasi identitas sekolah untuk dokumen cetak</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman ini digunakan untuk mengonfigurasi data yang akan muncul pada dokumen resmi cetak seperti Buku Induk dan rapor. Pastikan semua informasi diisi dengan benar sebelum mulai mencetak dokumen.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Pengaturan → Konfigurasi → Dokumen</strong> di sidebar kiri.</p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Pengaturan Dokumen</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/settings-dokumen.png') }}" alt="Pengaturan Dokumen" class="w-full h-auto"> --}}
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Bagian-bagian yang perlu dikonfigurasi:</p>
                <div class="space-y-3">
                    @php
                    $sections = [
                        ['sky', 'Identitas Institusi & Kepala Sekolah', [
                            'Nama Sekolah — nama resmi sekolah yang muncul di kop dokumen.',
                            'Jenjang Pendidikan — pilih SD, SMP, atau SMA/SMK.',
                            'Nama Kepala Sekolah — nama lengkap beserta gelar.',
                            'NIP / NIK Kepala Sekolah — nomor induk kepegawaian.',
                        ]],
                        ['amber', 'Detail Tempat & Tanggal Dokumen', [
                            'Kota/Tempat Penerbitan — akan muncul sebelum tanggal di dokumen (misal: "Gisting, 15 Juli 2024").',
                            'Tanggal Default — kosongkan agar otomatis menggunakan tanggal saat dokumen dicetak.',
                        ]],
                        ['emerald', 'Pengaturan Kertas & Margin PDF', [
                            'Ukuran Kertas — pilih A4, F4/Folio, Legal, Letter, atau Kustom.',
                            'Margin (Atas, Kanan, Bawah, Kiri) — dalam satuan centimeter (cm).',
                        ]],
                        ['indigo', 'Aset Visual (Logo, Kop & Pengesahan)', [
                            'Kop Surat — gambar kop surat sekolah (rasio melebar, contoh: 800×200 px).',
                            'Logo Sekolah — logo dalam format PNG/transparan, bentuk persegi.',
                            'Tanda Tangan Kepala Sekolah — file PNG dengan background transparan.',
                            'Stempel Sekolah — file PNG dengan background transparan.',
                        ]],
                    ];
                    @endphp
                    @foreach($sections as $section)
                    <div class="rounded-xl border border-{{ $section[0] }}-100 bg-{{ $section[0] }}-50/40 p-4">
                        <p class="text-sm font-bold text-{{ $section[0] }}-700 mb-2">{{ $section[1] }}</p>
                        <ul class="space-y-1">
                            @foreach($section[2] as $item)
                            <li class="flex items-start gap-2 text-xs text-slate-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $section[0] }}-400 flex-shrink-0 mt-1.5"></span>
                                {!! $item !!}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-amber-800 leading-relaxed">
                    <strong>Catatan penting:</strong> Setelah mengisi semua data, klik tombol <strong class="text-indigo-700">Simpan &amp; Perbarui</strong> di bagian bawah halaman. Perubahan tidak tersimpan otomatis.
                </p>
            </div>
        </div>
    </div>

    {{-- Langkah 6: Pengaturan Laman --}}
    <div id="langkah-6" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-rose-600">6</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pengaturan Tampilan Laman</h2>
                <p class="text-slate-500 text-sm mt-0.5">Kustomisasi konten halaman publik (landing page)</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman ini memungkinkan Anda mengubah konten teks, gambar, dan informasi yang ditampilkan pada halaman publik aplikasi (landing page) tanpa perlu menyentuh kode. Tersedia tiga tab pengaturan.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Pengaturan → Konfigurasi → Pengaturan Laman</strong> di sidebar kiri.</p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Pengaturan Laman</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/settings-pages.png') }}" alt="Pengaturan Laman" class="w-full h-auto"> --}}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-xs font-extrabold text-indigo-600">1</span>
                        <p class="text-sm font-bold text-indigo-700">Tab Beranda (Welcome)</p>
                    </div>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li class="flex items-start gap-1.5"><span class="text-indigo-400 flex-shrink-0 mt-0.5">•</span> Judul Hero (teks normal + highlight biru + highlight kuning)</li>
                        <li class="flex items-start gap-1.5"><span class="text-indigo-400 flex-shrink-0 mt-0.5">•</span> Subjudul hero</li>
                        <li class="flex items-start gap-1.5"><span class="text-indigo-400 flex-shrink-0 mt-0.5">•</span> Gambar hero utama (drag & drop)</li>
                        <li class="flex items-start gap-1.5"><span class="text-indigo-400 flex-shrink-0 mt-0.5">•</span> 2 badge melayang (judul + subjudul)</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-violet-100 bg-violet-50/50 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-6 h-6 rounded-lg bg-violet-100 flex items-center justify-center text-xs font-extrabold text-violet-600">2</span>
                        <p class="text-sm font-bold text-violet-700">Tab Tentang</p>
                    </div>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li class="flex items-start gap-1.5"><span class="text-violet-400 flex-shrink-0 mt-0.5">•</span> Judul dan subjudul header halaman Tentang</li>
                        <li class="flex items-start gap-1.5"><span class="text-violet-400 flex-shrink-0 mt-0.5">•</span> 3 kartu fitur unggulan (judul + deskripsi masing-masing)</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-100 flex items-center justify-center text-xs font-extrabold text-sky-600">3</span>
                        <p class="text-sm font-bold text-sky-700">Tab Kontak</p>
                    </div>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li class="flex items-start gap-1.5"><span class="text-sky-400 flex-shrink-0 mt-0.5">•</span> Judul dan subjudul halaman Kontak</li>
                        <li class="flex items-start gap-1.5"><span class="text-sky-400 flex-shrink-0 mt-0.5">•</span> Alamat / lokasi sekolah</li>
                        <li class="flex items-start gap-1.5"><span class="text-sky-400 flex-shrink-0 mt-0.5">•</span> Nomor telepon dan email resmi</li>
                    </ul>
                </div>
            </div>

            <div class="flex items-start gap-3 bg-slate-50 border border-slate-200 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-slate-600 leading-relaxed">Setiap tab memiliki tombol <strong>Simpan</strong> sendiri-sendiri. Pastikan klik tombol simpan pada tab yang sedang Anda edit sebelum berpindah tab.</p>
            </div>
        </div>
    </div>

    {{-- Langkah 7: Mata Pelajaran --}}
    <div id="langkah-7" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-teal-600">7</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Data Referensi Mata Pelajaran</h2>
                <p class="text-slate-500 text-sm mt-0.5">Master data mata pelajaran yang digunakan di formulir nilai</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman <strong>Mata Pelajaran</strong> berfungsi sebagai <em>master data</em> referensi. Daftar mata pelajaran yang dimasukkan di sini akan secara otomatis muncul sebagai pilihan saat mengisi nilai rapor dan prestasi belajar siswa.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Data Referensi → Mata Pelajaran</strong> di sidebar kiri.</p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Mata Pelajaran</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/mata-pelajaran.png') }}" alt="Mata Pelajaran" class="w-full h-auto"> --}}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-bold text-slate-700 mb-3">Cara menambah mata pelajaran:</p>
                    <ol class="space-y-2">
                        @foreach([
                            'Klik tombol <strong class="text-sky-600">+ Tambah Mapel</strong>.',
                            'Isi <strong>Nama Mata Pelajaran</strong> (contoh: Matematika).',
                            'Isi <strong>Kelompok</strong> — bisa memilih dari saran yang muncul (Muatan Nasional, Muatan Lokal, dll.) atau ketik sendiri.',
                            'Atur <strong>Urutan Tampil</strong> — angka kecil akan ditampilkan lebih dahulu.',
                            'Centang <strong>Aktif Digunakan</strong> jika mapel ini langsung dipakai.',
                            'Klik <strong>Simpan Baru</strong>.',
                        ] as $i => $step)
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-teal-100 flex items-center justify-center text-[0.65rem] font-extrabold text-teal-600 flex-shrink-0 mt-0.5">{{ $i+1 }}</span>
                            <p class="text-xs text-slate-600 leading-relaxed">{!! $step !!}</p>
                        </li>
                        @endforeach
                    </ol>
                </div>
                <div class="space-y-3">
                    <p class="text-sm font-bold text-slate-700">Kolom pada tabel:</p>
                    <div class="space-y-2">
                        @foreach([
                            ['Nama Mata Pelajaran', 'Nama lengkap mapel yang akan muncul di formulir nilai.'],
                            ['Kelompok / Kategori', 'Pengelompokan mapel (Muatan Nasional, Muatan Lokal, dst.).'],
                            ['Status', 'Aktif = tersedia di formulir. Non-aktif = tersembunyi dari pilihan.'],
                            ['Aksi', 'Tombol Edit (ikon pensil) dan Hapus (ikon tong sampah) untuk tiap baris.'],
                        ] as $col)
                        <div class="flex items-start gap-2 p-3 rounded-lg border border-slate-100 bg-slate-50/60">
                            <svg class="w-3.5 h-3.5 text-teal-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <div><p class="text-xs font-bold text-slate-700">{{ $col[0] }}</p><p class="text-xs text-slate-500">{{ $col[1] }}</p></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-3 bg-rose-50 border border-rose-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <p class="text-sm text-rose-800 leading-relaxed">
                    <strong>Perhatian:</strong> Menghapus mata pelajaran akan <strong>menghapus seluruh riwayat nilai</strong> siswa yang terhubung dengan mapel tersebut. Sebaiknya nonaktifkan mapel daripada menghapusnya.
                </p>
            </div>
        </div>
    </div>

    {{-- Langkah 8: Ekstrakurikuler --}}
    <div id="langkah-8" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-orange-600">8</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Data Referensi Ekstrakurikuler</h2>
                <p class="text-slate-500 text-sm mt-0.5">Master data kegiatan ekstrakurikuler sekolah</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman <strong>Ekstrakurikuler</strong> berfungsi sebagai master data referensi kegiatan non-akademik sekolah. Data ini akan muncul secara otomatis sebagai pilihan saat mengisi nilai ekskul pada semester siswa, dan hasilnya akan dicetak pada rapor serta Buku Induk.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Data Referensi → Ekstrakurikuler</strong> di sidebar kiri.</p>

            {{-- Placeholder Screenshot --}}
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Ekstrakurikuler</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
                {{-- <img src="{{ asset('images/docs/ekstrakurikuler.png') }}" alt="Ekstrakurikuler" class="w-full h-auto"> --}}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-bold text-slate-700 mb-3">Cara menambah ekstrakurikuler:</p>
                    <ol class="space-y-2">
                        @foreach([
                            'Klik tombol <strong class="text-indigo-600">+ Tambah Ekskul</strong>.',
                            'Isi <strong>Nama Ekstrakurikuler</strong> (contoh: Pramuka, Futsal, Seni Tari).',
                            'Isi <strong>Keterangan Tambahan</strong> jika diperlukan (opsional).',
                            'Klik <strong>Simpan Ekstrakurikuler</strong>.',
                        ] as $i => $step)
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center text-[0.65rem] font-extrabold text-orange-600 flex-shrink-0 mt-0.5">{{ $i+1 }}</span>
                            <p class="text-xs text-slate-600 leading-relaxed">{!! $step !!}</p>
                        </li>
                        @endforeach
                    </ol>
                </div>
                <div class="space-y-3">
                    <p class="text-sm font-bold text-slate-700">Fitur tambahan:</p>
                    <div class="space-y-2">
                        @foreach([
                            ['Pencarian & Filter', 'Gunakan kolom pencarian untuk menemukan ekskul berdasarkan nama atau keterangan.'],
                            ['Edit Data', 'Klik ikon pensil pada baris ekskul untuk mengubah nama atau keterangan.'],
                            ['Hapus Data', 'Klik ikon tong sampah. Tidak disarankan jika ekskul sudah memiliki riwayat nilai siswa.'],
                            ['Tombol Panduan', 'Ada tombol "Panduan" di pojok kanan atas untuk melihat ringkasan petunjuk langsung di halaman ini.'],
                        ] as $col)
                        <div class="flex items-start gap-2 p-3 rounded-lg border border-slate-100 bg-slate-50/60">
                            <svg class="w-3.5 h-3.5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <div><p class="text-xs font-bold text-slate-700">{{ $col[0] }}</p><p class="text-xs text-slate-500">{{ $col[1] }}</p></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 px-5 py-4">
                <p class="text-sm font-bold text-indigo-700 mb-1">Format nilai ekstrakurikuler di dokumen</p>
                <p class="text-xs text-slate-600 leading-relaxed">Nilai ekskul yang tercatat akan dicetak pada rapor dan Buku Induk dalam format <strong>predikat</strong>, contoh: <em>Sangat Baik</em>, <em>Baik</em>, <em>Cukup</em>. Pastikan nama ekskul sesuai dengan yang berlaku resmi di sekolah.</p>
            </div>
        </div>
    </div>

    {{-- Langkah 9: Data Pokok Siswa --}}
    <div id="langkah-9" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-indigo-600">9</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Data Pokok Siswa</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pusat data biodata dan fitur mutasi tahunan/semesteran</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman <strong>Data Pokok Siswa</strong> digunakan untuk mengelola data peserta didik. Anda dapat mencari data siswa, memperbarui tingkat kelas, serta melakukan impor data melalui format Dapodik maupun Master Buku Induk.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Data Master → Data Pokok Siswa</strong> di sidebar kiri.</p>

            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Data Pokok Siswa</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Fitur Impor Data
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2.5 text-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0 mt-1.5"></span>
                            <div>
                                <strong class="text-slate-700">Impor Dapodik</strong>
                                <p class="text-slate-500 text-xs mt-0.5">Mengunggah dataset yang diunduh langsung dari aplikasi Dapodik sekolah.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5 text-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 flex-shrink-0 mt-1.5"></span>
                            <div>
                                <strong class="text-slate-700">Master Buku Induk</strong>
                                <p class="text-slate-500 text-xs mt-0.5">Mengunggah data lengkap termasuk informasi orang tua, wali, dan riwayat mutasi siswa.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Fitur Mutasi &amp; Naik Kelas
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2.5 text-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                            <div>
                                <strong class="text-slate-700">Naik Semester</strong>
                                <p class="text-slate-500 text-xs mt-0.5">Digunakan saat pergantian semester ganjil ke genap untuk menyalin data siswa secara massal.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5 text-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0 mt-1.5"></span>
                            <div>
                                <strong class="text-slate-700">Naik Kelas</strong>
                                <p class="text-slate-500 text-xs mt-0.5">Digunakan untuk memproses kenaikan tingkat kelas di akhir tahun ajaran atau memproses kelulusan.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Cara Menggunakan Fitur Impor Data:</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Pastikan Anda telah berada pada tahun pelajaran aktif.', 'sky'],
                        ['Klik tombol <strong class="text-emerald-600">Impor Data</strong> yang berada di bagian kanan atas halaman.', 'emerald'],
                        ['Pilih format antara <strong>Master Buku Induk</strong> atau <strong>Format Dapodik</strong> sesuai basis data yang dimiliki.', 'indigo'],
                        ['Unduh <strong>Templat Excel</strong> yang disediakan pada jendela unggah.', 'amber'],
                        ['Lengkapi data peserta didik pada templat tersebut, lalu unggah berkas ke area yang tersedia.', 'sky'],
                        ['Klik tombol <strong>Mulai Impor Data</strong> dan tunggu sistem memproses data tersebut.', 'emerald'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">Cara Melakukan Mutasi Siswa (Naikan Semester/Kelas):</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Pilih opsi <strong class="text-indigo-600">Naikan Semester</strong> (ke semester genap) atau <strong class="text-amber-600">Naikan Kelas</strong> (ke tingkatan berikutnya) di pojok atas halaman.', 'indigo'],
                        ['Sebuah form layar-penuh akan muncul, memperlihatkan preview daftar siswa aktif dari semester sebelumnya yang berpeluang di mutasi.', 'sky'],
                        ['Periksa dengan teliti profil siswa dan jumlah data yang didapatkan pastikan kalkulasinya sinkron.', 'amber'],
                        ['Ketuk tombol biru/emas <strong>Salin Data Siswa</strong> / <strong>Proses Naik Kelas!</strong> di ujung kanan-bawah layar guna melaksanakan mutasi massal tersebut.', 'emerald'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="flex items-start gap-3 bg-rose-50 border border-rose-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <div>
                    <p class="text-sm font-bold text-rose-800 mb-0.5">Tahun Pelajaran Aktif Diperlukan</p>
                    <p class="text-xs text-rose-600 leading-relaxed">Semua fitur modifikasi dan impor data hanya akan tersedia jika <strong>Tahun Pelajaran</strong> sudah disetel dalam status aktif.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Langkah 10: Rombongan Belajar --}}
    <div id="langkah-10" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-sky-600">10</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Kelola Rombongan Belajar</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pembentukan kelompok kelas dan anggota per semesternya</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Modul <strong>Rombongan Belajar (Rombel)</strong> mengatur pemetaan siswa ke dalam kelas-kelas. Data rombel terikat pada satu tahun ajaran/semester yang aktif, sehingga setiap histori anggota kelas senantiasa terekam untuk cetak rapor per semester.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Data Master → Rombongan Belajar</strong>.</p>

            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Rombongan Belajar</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm font-bold text-slate-700">Kemampuan Modul Rombongan Belajar:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="border border-sky-100 bg-sky-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Tambah Kelas Baru</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Menset nama kelas, wali kelas yang mendampingi, dan jenis kurikulum yang diajukan.</p>
                    </div>

                    <div class="border border-indigo-100 bg-indigo-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V7M8 7h12m0 0v8a2 2 0 01-2 2h-2.5M12 7V4h3"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Salin Rombel &amp; Anggota</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Menciptakan tiruan susunan dan formasi siswa persis seperti kelompok belajar semester lalu.</p>
                    </div>

                    <div class="border border-emerald-100 bg-emerald-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Manajemen Anggota</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Tombol <strong class="text-slate-700">"Lihat Anggota"</strong> akan mengarahkan ke formasi presensi serta rapot tiap-tiap kelas.</p>
                    </div>
                </div>
            </div>
            
            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Cara Cepat Menyalin Rombongan Belajar dari Semester Sebelumnya:</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Pilih tombol ikon dokumen <strong class="text-indigo-600">Salin Rombel</strong> di sudut kanan atas antar-muka.', 'indigo'],
                        ['Layar pemilihan akan muncul, <strong>Pilih Semester Sumber</strong> memalui drop-down menu.', 'sky'],
                        ['Beri tanda centang pada list nama rombongan belajar mana yang ingin Anda teruskan keberadaannya di semester yang sedang aktif.', 'emerald'],
                        ['Lalu klik tombol <strong>Salin Rombel Terpilih</strong> supaya rancangan kelas tersebut tercipta.', 'indigo'],
                        ['Langkah kedua krusial, kembali ketuk opsi <strong class="text-emerald-600">Salin Anggota Rombel</strong> di atas lalu ulangi alur pemilihan sumber semester agar sistem mengirim para siswa/anggota masuk ke habitat kelas-kelas barunya secara presisi dan otomasi.', 'emerald'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Cara Mengelola Kelas Secara Manual:</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Gunakan tombol biru laut <strong class="text-sky-600">Tambah Rombel / Kelas</strong> andaikata kelas idaman Anda belum diciptakan, isi spesifikasi detail program/tingkat kurikulumnya di dalam pop-up.', 'sky'],
                        ['Klik opsi kuning <strong>Edit</strong> yang hadir di tiap baris rombel apabila memerlukan perbaikan nomenklatur kelas dan nama wali penghampunya.', 'amber'],
                        ['Tekan pilihan <strong>Lihat Anggota</strong> pada baris rombel yang bersangkutan untuk beralih masuk ke dalam panel detail kontrol dan mulai menambahkan partisipan kelas satu-persatu atau kelola nilai rapor.', 'indigo'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3 items-start">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm text-amber-800 leading-relaxed">
                    <strong>Catatan:</strong> Jika Anda bermaksud membubarkan suatu rombel yang telanjur berisi anggota pada semester ini, mohon pindahkan/mengeluarkan siswa dari rombel tersebut terlebih dahulu sebelum kelas dapat dinonaktifkan/dihapus dengan aman.
                </div>
            </div>
        </div>
    </div>

    {{-- Langkah 11: Buku Induk --}}
    <div id="langkah-11" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-teal-600">11</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Cetak &amp; Manajemen Buku Induk</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pemantauan progres dan pencetakan dokumen resmi Buku Induk Siswa</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman <strong>Buku Induk Siswa</strong> adalah bagian utama dari sistem ini. Di sini Anda dapat memantau kelengkapan data siswa melalui <strong>Bilah Kemajuan</strong> secara real-time. Pastikan seluruh data dilengkapi agar dokumen yang dihasilkan dalam format PDF menjadi valid dan resmi.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Buku Induk</strong> di panel sidebar kiri.</p>

            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Buku Induk</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm font-bold text-slate-700">Fitur Canggih dalam Modul Buku Induk:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="border border-emerald-100 bg-emerald-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Bilah Kemajuan</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Indikator visual kelengkapan data (merah/oranye/hijau) untuk setiap siswa.</p>
                    </div>

                    <div class="border border-sky-100 bg-sky-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Pencarian Langsung</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Fitur pencarian instan berdasarkan Nama atau NISN tanpa perlu memuat ulang halaman.</p>
                    </div>

                    <div class="border border-amber-100 bg-amber-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Filter Tingkat &amp; Kelas</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Kombinasi Dropdown (filter ganda paralel) untuk menyoroti kelompok target spesifik.</p>
                    </div>
                
                    <div class="border border-indigo-100 bg-indigo-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Cetak Lembar Resmi</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Kemampuan merangkai tata letak sistem menjadi lembar A4 PDF berstandar mutlak Buku Induk.</p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Cara Mengelola Buku Induk Individu:</p>
                <ol class="space-y-3">
                    @foreach([
                        ['Pilih profil siswa, perhatikan kolom Kelengkapan Data. Jika rentretan warnanya belum berubah progres hijau solid, maka data diri siswa tidak atau belum sempurna.', 'amber'],
                        ['Klik tombol biru terang <strong class="text-sky-600">Buka</strong> pada akhir baris kolom interaksi tabel siswa tersebut untuk dialihkan masuk ke Rincian Identitas.', 'sky'],
                        ['Di dalam mode rincian, klik tombol kuning/biru <strong>Lengkapi Data Induk</strong> di header-atas untuk memunculkan formulir isian multi-aspek (Biodata, Data Ortu, Kesehatan Pendaftar, dll).', 'indigo'],
                        ['Setelah seluruh tab formulir kelengkapan diselesaikan secara seksama, simpan, dan manfaatkan opsi cetak seperti <strong class="text-emerald-700">Cetak Dokumen Lengkap</strong> di Pustaka Cetak Dokumen.', 'emerald'],
                    ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-{{ $step[1] }}-100 border border-{{ $step[1] }}-200 flex items-center justify-center text-xs font-extrabold text-{{ $step[1] }}-600 flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{!! $step[0] !!}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="space-y-4 pt-6 border-t border-slate-100">
                <p class="text-sm font-bold text-slate-700 mb-3">Isi Formulir Rekam Jejak Buku Induk (10 Aspek Utama):</p>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">Formulir kelengkapan buku induk mengusung tampilan berbasis Tab (Layar Bergaya Navigasi Horizontal) untuk efisiensi ruang agar form isian super-komprehensif tidak terasa menghempas pandangan mata sekaligus.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px]">1</span> Identitas Murid</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Merangkum data diri paling dasar (NISN, NIK, Tempat/Tgl Lahir, Agama & dsb).</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px]">2</span> Data Orang Tua</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Kolom biografi sang Ayah, Ibu, beserta wali (<strong>Catatan Pintar:</strong> Jika pada tab periodik anak disetel tinggal dengan wali, form wali tiba-tiba jadi Wajib Diisi!).</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px]">3</span> Data Periodik</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Mendata perihal tempat bermukim si anak (jumlah sdr, jarak ke sekolah, bahasa ibu).</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px]">4</span> Pendidikan Sebelumnya</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Menjejak asal muasal anak baik dari lembaga tamatan pendidikan usia dini (TK) maupun berkas riwayat transisi kepindahan sekolah.</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px]">5</span> Jasmani & Foto</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Berisi form bobot tubuh si anak (Termasuk penyilangan tab input upload foto formal/pasphoto Buku Induk cetak minimal 2 varian).</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4 relative overflow-hidden">
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                        <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2"><span class="w-5 h-5 rounded bg-amber-100 text-amber-600 flex items-center justify-center text-[10px]">6</span> Dinamis: Beasiswa & Mutasi Keluar</p>
                        <p class="text-xs text-slate-500 leading-relaxed ml-7">Kolom ini tidak dibatasi isiannya (Bisa di klik "Tambah Catatan Baru" sesuka hati). Merangkum riwayat penikmatan beasiswa PIP hingga catatan registrasi hari kelulusan / rekam jejak kepindahan (mutasi keluar sekolah anak bersama pencetakan surat Ijazah).</p>
                    </div>
                </div>

                <div class="mt-4 p-5 rounded-xl border-2 border-indigo-100 bg-white shadow-sm relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-500"></div>
                    <h5 class="text-sm font-black text-indigo-800 flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Eksklusif (Semesteran): Input Nilai & Ekstrakurikuler
                    </h5>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">Nilai Akademik &amp; Ekskul tidak sekadar diisi biasa seperti memencet keyboard di form, namun tersistematis per-semester aktif.</p>
                    
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center text-xs font-black text-violet-600 shrink-0">A</span>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Prestasi Akademik (Nilai Rapor)</p>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Nilai kognitif, afektif, kepribadian serta matriks ketidakhadiran direkam via <strong class="text-emerald-600">Pop-up Editor Semester Khusus.</strong> Anda bahkan bisa menyuntikkan ribuan nilai sekaligus menggunakan Excel di fitur <strong class="text-indigo-600">Import Excel Khusus Nilai</strong>. Form akan membaca referensi dari Langkah 7.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-orange-100 border border-orange-200 flex items-center justify-center text-xs font-black text-orange-600 shrink-0">B</span>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Skor / Predikat Ekstrakurikuler</p>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Nilai non-akademik berupa predikat (Sangat Baik / A, Baik / B) diekskusi dari panel seraya membawa referensi dari Langkah 8. Sistem akan melahirkan lembar tabel histori riwayat eksplorasi non-akademik ini semester per semesternya.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="flex items-start gap-3 bg-teal-50 border border-teal-100 rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm text-teal-800 leading-relaxed">
                    <strong>Penting:</strong> Untuk dokumen yang dikalkulasi cetak, pastikan stelan fundamental seperti rasio Kop Surat, resolusi Stempel Kepala Sekolah, ukuran Tanda Tangan, serta presisi Margin kertas telah disusun rapi di <strong>Langkah 5 (Konfigurasi Dokumen)</strong>. Hasil cetakan halaman ini menjadi jejak arsip paling berharga institusi!
                </div>
            </div>
        </div>
    </div>

    {{-- Langkah 12: Data Alumni --}}
    <div id="langkah-12" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-violet-600">12</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Data Alumni (Kelulusan)</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pemantauan arsip daftar peserta didik yang telah berstatus Tamat Belajar/Lulus.</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Halaman <strong>Data Alumni</strong> adalah etalase yang menampung seluruh riwayat memori anak didik yang telah berlabuh menjadi Alumni (Tamat Belajar). Ini bukanlah sekadar tabel daftar hitam, tapi repositori pangkalan data yang masih memampangkan rekam jejak utuh buku induk mereka meski mereka telah menempuh perjalanan baru.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Arsip Siswa → Alumni</strong>.</p>

            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9-5 9 5-9 5zm0-14l9 5-9 5-9-5 9-5z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Screenshot Halaman Data Alumni</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm font-bold text-slate-700">Elemen Penting pada Panel Data Alumni:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="border border-violet-100 bg-violet-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Filter Lacak Tahun Kelulusan</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Berfasilitas sistem <em>Dropdown Cerdas</em> (Auto-submit), dimana Anda bebas menyeleksi dan menyeret kelompok kelulusan berdasarkan sesi Tahun Pelajarannya.</p>
                    </div>

                    <div class="border border-sky-100 bg-sky-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Kilas Balik Ijazah</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Tampilan tabel menyajikan relasi langsung ke rekaman Nomor Buku Ijazah dan data rujukan <em>Melanjutkan Ke instansi tingkat mana?</em>.</p>
                    </div>

                    <div class="border border-emerald-100 bg-emerald-50/50 rounded-xl p-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Cetak Ulang Buku Induk</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Kendati status mereka bukan siswa aktif, menekan tombol <span class="bg-violet-100 text-violet-700 px-1 py-0.5 rounded ml-0.5">Buka</span> akan meretas jalan ke Pustaka Cetak Induk individual mereka (Tervalidasi Abadi).</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex gap-3 items-start mt-4">
                <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm text-indigo-800 leading-relaxed">
                    <strong>Informasi:</strong> Bagaimana anak berstatus Alumni? Data ini di otomatisasi oleh sistem. Anda tidak bisa mendaftarkan anak kesini secara paksa manual. Mekanismenya bermula kala siswa bersangkutan di-input dengan form status pendaftaran mutasi <strong>Tamat Belajar</strong> (Meninggalkan Sekolah) pada ruang Buku Induk. Otomatisasi pangkalan data menjustifikasinya murni sebagai Alumni!
                </div>
            </div>
        </div>
    </div>

    <div id="langkah-13" class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden print:shadow-none print:border print:border-slate-300 print:mb-8">
        <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                <span class="text-xl font-extrabold text-rose-600">13</span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Arsip Terhapus & Pemulihan</h2>
                <p class="text-slate-500 text-sm mt-0.5">Manajemen pemulihan atau penghapusan permanen data siswa.</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Sistem ini menerapkan mekanisme penghapusan lunak (<em>Soft-Delete</em>). Artinya, saat Anda menghapus data (seperti Data Pokok Siswa), data tersebut tidak langsung terhapus secara permanen dari server. Data akan dipindahkan ke folder <strong>Arsip Terhapus</strong> untuk memitigasi kesalahan penghapusan yang tidak disengaja.
            </p>
            <p class="text-sm text-slate-500">Akses melalui menu: <strong>Kelola → Arsip Terhapus</strong>.</p>

            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Tangkapan Layar Arsip Terhapus</p>
                    <p class="text-xs text-slate-400 mt-1">[ Gambar akan ditambahkan ]</p>
                </div>
            </div>

            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Dua Tindakan Utama di Menu Arsip Terhapus:</p>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-xs font-black text-emerald-600 shrink-0">A</span>
                        <div>
                            <p class="text-sm font-bold text-emerald-700">Opsi: Pulihkan (Mengembalikan Data)</p>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Klik tombol berwarna hijau untuk mengaktifkan kembali data siswa ke halaman asal (Data Pokok Siswa/Buku Induk). Ini akan membatalkan status penghapusan data.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center text-xs font-black text-rose-600 shrink-0">B</span>
                        <div>
                            <p class="text-sm font-bold text-rose-700">Opsi: Hapus Permanen (Penghapusan Selamanya)</p>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Tombol merah ini akan menghapus seluruh data siswa (termasuk catatan Buku Induk) dari server secara permanen. <strong>Tindakan ini tidak dapat dibatalkan.</strong> Gunakan dengan penuh kewaspadaan.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Footer Docs --}}
    <div class="bg-slate-100/70 rounded-2xl border border-slate-200 px-8 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 print:hidden">
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Dokumentasi Aplikasi</p>
            <p class="text-sm text-slate-600">SD Muhammadiyah Gisting &mdash; Sistem Informasi Buku Induk</p>
        </div>
        <button onclick="window.print()"
                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white font-semibold text-sm transition-all cursor-pointer shadow-sm shadow-indigo-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .print\:hidden { display: none !important; }
        .print\:shadow-none { box-shadow: none !important; }
        .print\:border-0 { border: none !important; }
        .print\:border { border-width: 1px !important; }
        .print\:border-slate-300 { border-color: #cbd5e1 !important; }
        .print\:mb-8 { margin-bottom: 2rem !important; }
        .print\:text-black { color: #000 !important; }
        a[href]:after { content: none !important; }
        #docs-content { max-width: 100% !important; }
    }
</style>
@endsection
