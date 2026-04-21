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
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Cara Memulai Menggunakan Aplikasi</h1>
                    <p class="text-indigo-100 text-base max-w-xl leading-relaxed">
                        Panduan langkah demi langkah untuk mengakses dan masuk ke Sistem Informasi Buku Induk SD Muhammadiyah Gisting.
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
                <p class="text-slate-500 text-sm mt-0.5">Halaman pertama yang akan Anda temui saat membuka aplikasi</p>
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
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Masuk ke Sistem (Login)</h2>
                <p class="text-slate-500 text-sm mt-0.5">Gunakan akun yang telah diberikan oleh Administrator</p>
            </div>
        </div>

        <div class="px-8 py-6 space-y-6">

            {{-- Penjelasan --}}
            <div>
                <p class="text-slate-600 leading-relaxed">
                    Setelah mengklik tombol <strong>Login</strong> di halaman utama, Anda akan diarahkan ke <strong>Halaman Login</strong>.
                    Di sini Anda perlu memasukkan kredensial (data masuk) yang telah diberikan oleh Administrator sekolah.
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
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Mengenal Dashboard</h2>
                <p class="text-slate-500 text-sm mt-0.5">Halaman ikhtisar setelah berhasil login</p>
            </div>
        </div>
        <div class="px-8 py-6 space-y-6">
            <p class="text-slate-600 leading-relaxed">
                Setelah login berhasil, Anda akan diarahkan ke <strong>Dashboard</strong>. Halaman ini menampilkan ringkasan data dan kondisi sistem secara menyeluruh sehingga Anda dapat langsung memantau keadaan terkini tanpa harus membuka halaman lain.
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
