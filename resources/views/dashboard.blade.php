@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Induk Siswa')
@section('breadcrumb', 'Ikhtisar Sistem')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalSiswaAktif, 0, ',', '.') }}</h3>
            <p class="text-xs font-semibold text-emerald-500 flex items-center gap-1 mt-2 tracking-tight">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                +2.5% bulan ini
            </p>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Alumni & Lulusan</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalAlumni, 0, ',', '.') }}</h3>
            <p class="text-xs font-medium text-slate-400 mt-2">Seluruh Angkatan Lulus</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Rombongan Belajar</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalRombel }}</h3>
            <p class="text-xs font-medium text-slate-400 mt-2">Pecahan Kelas Aktif</p>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div class="w-full">
            <p class="text-sm font-semibold text-slate-500 mb-1">Kelengkapan Buku Induk</p>
            <div class="flex items-end justify-between mb-2">
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $avgKelengkapan }}%</h3>
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-2 mt-1 truncate">
                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $avgKelengkapan }}%"></div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content List -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Siswa Pindahan Baru</h3>
            <a href="#" class="text-sm font-semibold text-sky-600 hover:text-sky-700">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/50 text-slate-400 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-4 px-6">Nama Siswa / NISN</th>
                        <th class="py-4 px-6">Asal Sekolah</th>
                        <th class="py-4 px-6">Tanggal Diterima</th>
                        <th class="py-4 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaBaru as $siswa)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">{{ $siswa->nama }}</p>
                            <p class="text-xs text-slate-400 font-mono">{{ $siswa->nisn ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-600 truncate max-w-[150px]">{{ $siswa->asal_sekolah ?? '-' }}</td>
                        <td class="py-4 px-6 font-medium text-slate-600">{{ $siswa->created_at->translatedFormat('d M Y') }}</td>
                        <td class="py-4 px-6 text-right">
                            @if($siswa->status == 'Aktif')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[0.65rem] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Aktif Terdaftar</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[0.65rem] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $siswa->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 text-sm font-medium">Belum ada data siswa baru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Sidebar Content -->
    <div class="flex flex-col gap-6">

        @hasrole('Super Admin')
        <!-- Aksi Cepat: Toggle Tahun Pelajaran -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-md border border-indigo-400 overflow-hidden text-white relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
            <div class="p-6 relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-indigo-400/30 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-base font-extrabold tracking-tight">Aksi Cepat</h3>
                </div>
                <p class="text-xs text-indigo-100 font-medium mb-3">Tahun Pelajaran Aktif Saat Ini:</p>
                <form action="{{ $tahunAktif ? route('tahun-pelajaran.activate', $tahunAktif->id) : '#' }}" method="POST" id="quick-activate-form">
                    @csrf
                    @method('PATCH')
                    <select name="tahun_id" onchange="if(this.value){ document.getElementById('quick-activate-form').action = '/tahun-pelajaran/' + this.value + '/activate'; document.getElementById('quick-activate-form').submit(); }" class="w-full bg-indigo-900/40 border border-indigo-400/50 rounded-xl px-4 py-2.5 text-sm font-bold text-white focus:ring-2 focus:ring-white/20 focus:outline-none appearance-none cursor-pointer">
                        <option value="" disabled {{ !$tahunAktif ? 'selected' : '' }}>Pilih Tahun Pelajaran</option>
                        @foreach($tahunPelajarans as $tp)
                            <option value="{{ $tp->id }}" class="text-slate-800" {{ $tahunAktif && $tahunAktif->id == $tp->id ? 'selected' : '' }}>{{ $tp->tahun }} - Semester {{ $tp->semester }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        @endhasrole

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Aktivitas Terakhir</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @forelse($aktivitas as $index => $act)
                    <div class="flex gap-4">
                        <div class="relative flex flex-col items-center">
                            @if($loop->first)
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 z-10 shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            @else
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 z-10 shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </div>
                            @endif
                            @if(!$loop->last)
                            <div class="w-px h-full bg-slate-200 absolute top-8 bottom-[-1.5rem]"></div>
                            @endif
                        </div>
                        <div class="pb-1">
                            <p class="text-sm font-bold text-slate-800">Modifikasi berkas {{ $act->nama }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $act->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 font-medium text-center">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
