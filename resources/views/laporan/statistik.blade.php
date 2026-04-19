@extends('layouts.app')

@section('title', 'Laporan & Statistik')
@section('header_title', 'Laporan & Statistik')
@section('breadcrumb', 'Laporan Statistik')

@section('content')
@php
    $totalSiswaAktif = collect($siswaPerTingkat)->sum('total');
    $totalAlumni     = $siswaPerStatus['Lulus'];
    $totalKeluar     = $siswaPerStatus['Keluar/Mutasi'];
@endphp

{{-- ── Hero Header ─────────────────────────────────────────────────────────── --}}
<div class="relative mb-8 text-white z-20">
    {{-- Background & Decorative Layer (Clipped) --}}
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl shadow-xl overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-10 top-10 w-20 h-20 bg-indigo-400/20 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
    </div>

    {{-- Content Layer (Overflow Visible) --}}
    <div class="relative z-10 p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold mb-2 tracking-tight">Laporan &amp; Statistik</h2>
            <p class="text-indigo-100 font-medium max-w-xl">
                Ringkasan data siswa, alumni, dan buku induk
                @if($tahunAktif)
                    untuk sesi <span class="font-bold text-white">{{ $tahunAktif->tahun }} – Semester {{ $tahunAktif->semester }}</span>.
                @else
                    (belum ada tahun aktif).
                @endif
            </p>
            <div class="mt-4 inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-2xl">
                <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-bold">
                    Total Siswa Aktif:
                    <span class="text-white text-lg ml-1">{{ number_format($totalSiswaAktif, 0, ',', '.') }}</span>
                </span>
            </div>
        </div>

        {{-- Export Alumni Form --}}
        <div x-data="{ open: false }" class="relative shrink-0">
            <button @click="open = !open"
                    class="flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-900/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Export Alumni Excel
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 z-50"
                 x-cloak>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Filter Tahun Pelajaran</p>
                <form method="POST" action="{{ route('laporan.alumni.export') }}">
                    @csrf
                    <div class="relative mb-3">
                        <select name="tahun_id"
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none appearance-none shadow-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $tp)
                                <option value="{{ $tp->id }}">{{ $tp->tahun }} – Semester {{ $tp->semester }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Unduh Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Card: Total Siswa Aktif --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Siswa Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalSiswaAktif, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1 font-medium">
                L: {{ number_format($siswaPerJK['L'], 0, ',', '.') }} &nbsp;/&nbsp; P: {{ number_format($siswaPerJK['P'], 0, ',', '.') }}
            </p>
        </div>
        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300 shrink-0">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
    </div>

    {{-- Card: Total Alumni --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Alumni (Lulus)</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalAlumni, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1 font-medium">Keluar/Mutasi: {{ number_format($totalKeluar, 0, ',', '.') }}</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shrink-0">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    {{-- Card: Buku Induk --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Buku Induk</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalBukuInduk, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1 font-medium">
                Data lengkap (ada foto): {{ number_format($bukuIndukLengkap, 0, ',', '.') }}
            </p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shrink-0">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
    </div>
</div>

{{-- ── Two-column detail grid ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Tabel: Siswa per Tingkat Kelas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50 flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Siswa per Tingkat Kelas</h3>
                <p class="text-xs text-slate-500 font-medium">Tahun aktif · hanya status Aktif</p>
            </div>
        </div>

        @if($siswaPerTingkat->isEmpty())
            <div class="p-8 text-center">
                <p class="text-slate-400 text-sm font-medium">Belum ada data siswa pada tahun aktif.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Rombel</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Laki-laki</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Perempuan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($siswaPerTingkat as $tingkat => $row)
                    @php
                        $jumlahRombel = $rombelPerTingkat->get($tingkat)?->total ?? 0;
                        // Per-tingkat JK counts are not pre-computed; show from aggregate
                        // We use the per-JK counts only at the total level above.
                        // For per-tingkat JK we display "–" unless we compute them.
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3 font-bold text-slate-800">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-extrabold text-xs flex items-center justify-center">{{ $tingkat }}</span>
                                Kelas {{ $tingkat }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-2.5 py-1 rounded-lg">
                                {{ $jumlahRombel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sky-700 font-semibold">–</td>
                        <td class="px-4 py-3 text-center text-rose-600 font-semibold">–</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block bg-indigo-50 text-indigo-700 border border-indigo-100 text-sm font-extrabold px-3 py-1 rounded-xl">
                                {{ number_format($row->total, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                        <td class="px-4 py-3 font-extrabold text-slate-700 text-sm">Total</td>
                        <td class="px-4 py-3 text-center font-bold text-slate-600 text-sm">
                            {{ $rombelPerTingkat->sum('total') }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-sky-700 text-sm">
                            {{ number_format($siswaPerJK['L'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-rose-600 text-sm">
                            {{ number_format($siswaPerJK['P'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center font-extrabold text-indigo-700 text-sm">
                            {{ number_format($totalSiswaAktif, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>

    {{-- Tabel: Status Siswa --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50 flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Status Siswa</h3>
                <p class="text-xs text-slate-500 font-medium">Lintas semua tahun pelajaran</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            {{-- Aktif --}}
            @php $totalAll = array_sum($siswaPerStatus); @endphp
            @foreach([
                ['label' => 'Aktif',         'key' => 'Aktif',         'bg' => 'bg-sky-50',     'border' => 'border-sky-200',   'text' => 'text-sky-700',     'dot' => 'bg-sky-500'],
                ['label' => 'Lulus / Alumni','key' => 'Lulus',         'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200','text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
                ['label' => 'Keluar/Mutasi', 'key' => 'Keluar/Mutasi', 'bg' => 'bg-rose-50',    'border' => 'border-rose-200',  'text' => 'text-rose-700',    'dot' => 'bg-rose-500'],
            ] as $item)
            @php
                $count   = $siswaPerStatus[$item['key']];
                $pct     = $totalAll > 0 ? round($count / $totalAll * 100, 1) : 0;
            @endphp
            <div class="{{ $item['bg'] }} border {{ $item['border'] }} rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ $item['dot'] }}"></span>
                        <span class="text-sm font-bold {{ $item['text'] }}">{{ $item['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-extrabold {{ $item['text'] }}">
                            {{ number_format($count, 0, ',', '.') }}
                        </span>
                        <span class="text-xs font-semibold {{ $item['text'] }} opacity-70">({{ $pct }}%)</span>
                    </div>
                </div>
                <div class="w-full bg-white/60 rounded-full h-2 overflow-hidden">
                    <div class="{{ $item['dot'] }} h-2 rounded-full transition-all duration-500"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach

            <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Seluruh Data</span>
                <span class="text-lg font-extrabold text-slate-800">{{ number_format($totalAll, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabel Trend per Tahun Pelajaran ─────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50 flex items-center gap-3">
        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-slate-800">Trend Siswa per Tahun Pelajaran</h3>
            <p class="text-xs text-slate-500 font-medium">Perbandingan jumlah siswa antar tahun pelajaran</p>
        </div>
    </div>

    @if($trendPerTahun->isEmpty())
        <div class="p-8 text-center">
            <p class="text-slate-400 text-sm font-medium">Belum ada data tahun pelajaran.</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun Pelajaran</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Semester</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Total Siswa</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa Aktif</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Lulus</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Proporsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($trendPerTahun as $row)
                @php
                    $barWidth = $trendPerTahun->max('total') > 0
                        ? round($row['total'] / $trendPerTahun->max('total') * 100)
                        : 0;
                    $isAktif  = $tahunAktif && $tahunAktif->tahun === $row['tahun'] && $tahunAktif->semester === $row['semester'];
                @endphp
                <tr class="hover:bg-slate-50/70 transition-colors {{ $isAktif ? 'ring-1 ring-inset ring-indigo-200 bg-indigo-50/30' : '' }}">
                    <td class="px-6 py-3 font-bold text-slate-800">
                        {{ $row['tahun'] }}
                        @if($isAktif)
                            <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[0.65rem] font-bold uppercase tracking-wide border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="inline-block bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                            {{ $row['semester'] }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center font-extrabold text-slate-800">
                        {{ number_format($row['total'], 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="inline-block bg-sky-50 text-sky-700 border border-sky-200 text-xs font-bold px-2.5 py-1 rounded-lg">
                            {{ number_format($row['baru'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-2.5 py-1 rounded-lg">
                            {{ number_format($row['lulus'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $barWidth }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500 w-8 text-right">{{ $barWidth }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
