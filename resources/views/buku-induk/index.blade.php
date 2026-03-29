@extends('layouts.app')

@section('title', 'Buku Induk Siswa')
@section('header_title', 'Buku Induk Siswa')
@section('breadcrumb', 'Buku Induk')

@section('content')
<div class="space-y-6">
    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Buku Induk Siswa</h2>
            <p class="text-sm text-slate-500 mt-1">Arsip digital permanen — tersedia untuk seluruh siswa aktif maupun alumni.</p>
        </div>
        <div class="flex gap-2 items-center">
            @foreach(['Aktif', 'Lulus', 'Semua'] as $st)
                <a href="{{ route('buku-induk.index', ['status' => $st, 'q' => $search]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $statusFilter == $st ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-500 border-slate-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    {{ $st }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('buku-induk.index') }}" class="flex gap-3">
        <input type="hidden" name="status" value="{{ $statusFilter }}">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama siswa atau NISN..."
                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm font-medium text-slate-700">
        </div>
        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5 cursor-pointer">
            Cari
        </button>
    </form>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Student Grid --}}
    @if($siswas->isEmpty())
    <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center shadow-sm">
        <div class="w-20 h-20 bg-indigo-50 text-indigo-300 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Tidak Ada Data</h3>
        <p class="text-slate-500 mt-1 text-sm">Tidak ada siswa yang cocok dengan pencarian Anda.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($siswas as $siswa)
        @php
            $bi = $bukuIndukMap[$siswa->nisn] ?? null;
            $kelengkapan = $bi ? $bi->kelengkapan : 0;
            $statusColor = match($siswa->status) {
                'Aktif' => 'bg-emerald-100 text-emerald-700',
                'Lulus' => 'bg-sky-100 text-sky-700',
                'Keluar/Mutasi' => 'bg-rose-100 text-rose-700',
                default => 'bg-slate-100 text-slate-600',
            };
            $progressColor = $kelengkapan >= 80 ? 'bg-emerald-500' : ($kelengkapan >= 40 ? 'bg-amber-400' : 'bg-rose-400');
        @endphp
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all group overflow-hidden">
            {{-- Top Gradient --}}
            <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            <div class="p-5">
                {{-- Avatar & Name --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-base shadow-sm flex-shrink-0">
                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 leading-tight truncate text-sm">{{ $siswa->nama }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $siswa->nisn ?? 'NISN -' }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-[0.6rem] font-bold rounded-full {{ $statusColor }}">
                            {{ $siswa->status ?? 'Aktif' }}
                        </span>
                    </div>
                </div>

                {{-- Rombel --}}
                <p class="text-xs text-slate-500 font-medium mb-3">
                    <span class="font-bold text-slate-600">{{ $siswa->rombel_saat_ini ?? 'Kelas tidak diketahui' }}</span>
                </p>

                {{-- Completeness Progress --}}
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider">Kelengkapan Buku Induk</span>
                        <span class="text-[0.65rem] font-black {{ $kelengkapan >= 80 ? 'text-emerald-600' : ($kelengkapan >= 40 ? 'text-amber-600' : 'text-rose-500') }}">{{ $kelengkapan }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $progressColor }} transition-all" style="width: {{ $kelengkapan }}%"></div>
                    </div>
                </div>

                {{-- Action --}}
                @if($bi)
                <a href="{{ route('buku-induk.show', $siswa->nisn) }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold rounded-xl transition-all border border-indigo-100 hover:border-indigo-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Buka Buku Induk
                </a>
                @else
                <span class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-50 text-slate-400 text-xs font-bold rounded-xl border border-slate-100 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Belum Ada NISN
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($siswas->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-6 py-4">
        {{ $siswas->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
