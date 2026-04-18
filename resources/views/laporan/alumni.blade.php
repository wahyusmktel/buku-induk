@extends('layouts.app')

@section('title', 'Laporan Alumni')
@section('header_title', 'Laporan Alumni')
@section('breadcrumb', 'Laporan Alumni')

@section('content')

{{-- ── Hero Header ─────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl shadow-xl p-8 mb-8 relative overflow-hidden text-white">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold mb-2 tracking-tight">Laporan Alumni</h2>
            <p class="text-emerald-100 font-medium max-w-xl">
                Data seluruh siswa yang telah lulus dari sekolah.
            </p>
            <div class="mt-4 inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-bold">
                    Total Alumni:
                    <span class="text-white text-lg ml-1">{{ number_format($totalAlumni, 0, ',', '.') }}</span>
                </span>
            </div>
        </div>

        {{-- Export Button --}}
        <div class="shrink-0 flex flex-col gap-2">
            <form method="POST" action="{{ route('laporan.alumni.export') }}">
                @csrf
                @if($tahunId)
                    <input type="hidden" name="tahun_id" value="{{ $tahunId }}">
                @endif
                <button type="submit"
                        class="flex items-center gap-2 bg-white text-emerald-700 hover:bg-emerald-50 px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-900/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    Export Excel
                </button>
            </form>
        </div>
    </div>

    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-10 top-10 w-20 h-20 bg-emerald-400/20 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
</div>

{{-- ── Two-Column Layout: Stats + Filter ──────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- Alumni Per Tahun --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Alumni per Tahun Pelajaran</h3>
                <p class="text-xs text-slate-500 font-medium">Klik untuk filter</p>
            </div>
        </div>
        <div class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
            @forelse($alumniPerTahun as $tp)
            <a href="{{ route('laporan.alumni', ['tahun_id' => $tp->id]) }}"
               class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors {{ $tahunId == $tp->id ? 'bg-emerald-50' : '' }}">
                <div>
                    <p class="text-sm font-semibold text-slate-700 {{ $tahunId == $tp->id ? 'text-emerald-700' : '' }}">
                        {{ $tp->tahun }}
                    </p>
                    <p class="text-xs text-slate-400 font-medium">Semester {{ $tp->semester }}</p>
                </div>
                <span class="inline-flex items-center justify-center min-w-[2rem] h-7 px-2 rounded-full text-xs font-black
                    {{ $tahunId == $tp->id ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $tp->alumni_count }}
                </span>
            </a>
            @empty
            <div class="p-6 text-center text-slate-400 text-sm">Belum ada data alumni.</div>
            @endforelse
        </div>
        @if($tahunId)
        <div class="px-5 py-3 border-t border-slate-100">
            <a href="{{ route('laporan.alumni') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                Tampilkan semua tahun
            </a>
        </div>
        @endif
    </div>

    {{-- Filter & Search --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                Filter Pencarian
            </h3>
            <form method="GET" action="{{ route('laporan.alumni') }}" class="flex flex-col sm:flex-row gap-3">
                {{-- Tahun Filter --}}
                <div class="relative flex-1">
                    <select name="tahun_id"
                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none appearance-none shadow-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunPelajarans as $tp)
                            <option value="{{ $tp->id }}" {{ $tahunId == $tp->id ? 'selected' : '' }}>
                                {{ $tp->tahun }} – Semester {{ $tp->semester }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Cari nama atau NISN…"
                           class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all">
                </div>

                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    Cari
                </button>

                @if(request('q') || $tahunId)
                <a href="{{ route('laporan.alumni') }}"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-xl transition-colors whitespace-nowrap flex items-center">
                    Reset
                </a>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- ── Alumni Table ─────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Alumni</h3>
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan {{ $alumni->firstItem() }}–{{ $alumni->lastItem() }} dari {{ $alumni->total() }} data
                </p>
            </div>
        </div>
    </div>

    @if($alumni->isEmpty())
        <div class="p-10 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-slate-400 font-semibold">Tidak ada data alumni ditemukan.</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">NISN</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">JK</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun Lulus</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rombel</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Ijazah</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Lanjut Ke</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($alumni as $idx => $siswa)
                @php $bi = $bukuIndukMap->get($siswa->nisn); @endphp
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-4 py-3 text-slate-400 text-xs">{{ $alumni->firstItem() + $idx }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $siswa->nama }}</p>
                                @if($siswa->nipd)
                                    <p class="text-xs text-slate-400 font-mono">NIS: {{ $siswa->nipd }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $siswa->nisn ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                            {{ $siswa->jk === 'L' ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-600' }}">
                            {{ $siswa->jk }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($siswa->tahunPelajaran)
                            <span class="text-slate-700 font-semibold">{{ $siswa->tahunPelajaran->tahun }}</span>
                            <span class="text-slate-400 text-xs ml-1">Smt {{ $siswa->tahunPelajaran->semester }}</span>
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-600 font-medium text-xs">
                        {{ $siswa->rombel?->nama ?? ($siswa->rombel_saat_ini ?? '-') }}
                    </td>
                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">
                        {{ $bi?->no_ijazah ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($bi?->lanjut_ke)
                            <span class="inline-block px-2 py-0.5 bg-teal-50 text-teal-700 border border-teal-100 rounded-lg text-xs font-semibold">
                                {{ $bi->lanjut_ke }}
                            </span>
                        @else
                            <span class="text-slate-300 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($alumni->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between gap-4">
        <p class="text-xs text-slate-400 font-medium">
            Halaman {{ $alumni->currentPage() }} dari {{ $alumni->lastPage() }}
        </p>
        <div class="flex items-center gap-1">
            @if($alumni->onFirstPage())
                <span class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">
                    &laquo; Prev
                </span>
            @else
                <a href="{{ $alumni->previousPageUrl() }}"
                   class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    &laquo; Prev
                </a>
            @endif

            @foreach($alumni->getUrlRange(max(1, $alumni->currentPage()-2), min($alumni->lastPage(), $alumni->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                       {{ $page == $alumni->currentPage() ? 'bg-emerald-600 text-white border-emerald-600' : 'text-slate-600 bg-white border-slate-200 hover:bg-slate-50' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if($alumni->hasMorePages())
                <a href="{{ $alumni->nextPageUrl() }}"
                   class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Next &raquo;
                </a>
            @else
                <span class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">
                    Next &raquo;
                </span>
            @endif
        </div>
    </div>
    @endif
    @endif
</div>

@endsection
