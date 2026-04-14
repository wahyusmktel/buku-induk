@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Induk Siswa')
@section('breadcrumb', 'Ikhtisar Sistem')

@section('content')
<!-- Hero Welcome Section -->
<div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl shadow-xl p-8 mb-8 relative overflow-hidden text-white">
    <div class="relative z-10">
        <h2 class="text-3xl font-extrabold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-indigo-100 font-medium max-w-xl">Halaman dashboard telah dioptimalkan untuk performa maksimal. Kelola data induk siswa dengan cepat dan efisien.</p>
        
        <div class="flex gap-4 mt-8">
            <a href="{{ route('siswas.index') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-md px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 border border-white/10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Data Siswa
            </a>
            <a href="{{ route('buku-induk.index') }}" class="bg-indigo-500 hover:bg-indigo-400 px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-indigo-900/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                Buku Induk
            </a>
        </div>
    </div>
    <!-- Decorative Elements -->
    <img src="{{ asset('images/batik-kawung-motif.png') }}" class="absolute -right-4 -bottom-4 w-72 opacity-20 pointer-events-none" style="mask-image: linear-gradient(to bottom right, black, transparent); -webkit-mask-image: linear-gradient(to bottom right, black, transparent);">
    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute right-10 top-10 w-20 h-20 bg-indigo-400/20 rounded-full blur-2xl animate-pulse"></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalSiswaAktif, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Alumni</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalAlumni, 0, ',', '.') }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Rombel</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalRombel }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <!-- Quick Informative Card -->
        <div class="bg-sky-50 rounded-2xl p-6 border border-sky-100 flex gap-4 items-start">
            <div class="p-3 bg-sky-500 rounded-xl text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h4 class="text-sky-900 font-bold mb-1">Tips Navigasi</h4>
                <p class="text-sky-700 text-sm leading-relaxed">Gunakan menu di samping untuk mengakses fitur lengkap. Dashboard ini sengaja dibuat minimalis untuk memastikan kecepatan akses data yang optimal bagi manajemen sekolah.</p>
            </div>
        </div>
    </div>

    <div>
        @hasrole('Super Admin')
        <!-- Aksi Cepat: Toggle Tahun Pelajaran -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group/panel hover:shadow-md transition-shadow duration-300">
            <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 bg-indigo-500 rounded-lg text-white">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                    Aksi Cepat
                </h3>
            </div>
            <div class="p-5">
                <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wide">Tahun Pelajaran Aktif</p>
                @if($tahunAktif)
                    <div class="flex items-center gap-2 mb-4 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-sm font-bold text-emerald-700">{{ $tahunAktif->tahun }} - Semester {{ $tahunAktif->semester }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 mb-4 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        <span class="text-sm font-bold text-amber-700">Belum ada tahun pelajaran aktif</span>
                    </div>
                @endif

                <label for="tahun-select" class="text-xs text-slate-500 font-semibold block mb-2">Beralih ke:</label>
                <form action="#" method="POST" id="quick-activate-form">
                    @csrf
                    @method('PATCH')
                    <div class="relative">
                        <select name="tahun_id" id="tahun-select" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none appearance-none cursor-pointer shadow-sm hover:border-indigo-300 transition-colors">
                            <option value="" disabled selected>Pilih Tahun Pelajaran</option>
                            @foreach($tahunPelajarans as $tp)
                                <option value="{{ $tp->id }}" {{ $tahunAktif && $tahunAktif->id == $tp->id ? 'disabled class="opacity-50"' : '' }}>{{ $tp->tahun }} - Semester {{ $tp->semester }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                        </div>
                    </div>
                    <button type="button" id="btn-activate-tp" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Aktifkan
                    </button>
                </form>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('tahun-select');
            const btn = document.getElementById('btn-activate-tp');
            const form = document.getElementById('quick-activate-form');

            select.addEventListener('change', function() {
                btn.disabled = !this.value;
            });

            btn.addEventListener('click', function() {
                const selectedId = select.value;
                const selectedText = select.options[select.selectedIndex].text;

                if (!selectedId) return;

                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    html: '<div class="text-left">' +
                        '<p class="text-slate-600 mb-3">Apakah Anda yakin ingin beralih ke tahun pelajaran:</p>' +
                        '<div class="bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 text-center">' +
                        '<span class="text-base font-bold text-indigo-700">' + selectedText + '</span>' +
                        '</div>' +
                        '<p class="text-xs text-slate-400 mt-3">Data yang ditampilkan akan disesuaikan dengan tahun pelajaran yang dipilih.</p>' +
                        '</div>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Aktifkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.action = '/tahun-pelajaran/' + selectedId + '/activate';
                        form.submit();
                    } else {
                        select.value = '';
                        btn.disabled = true;
                    }
                });
            });
        });
        </script>
        @endhasrole
    </div>
</div>
@endsection
