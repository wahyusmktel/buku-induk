@extends('layouts.app')

@section('title', 'Daftar Rombel')
@section('header_title', 'Rombongan Belajar')
@section('breadcrumb', 'Daftar Rombel')

@section('content')
<div class="space-y-6">
    @if(!$tahunAktif)
    <div class="bg-amber-50 border-2 border-amber-200 border-dashed rounded-3xl p-8 text-center shadow-sm">
        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-black text-amber-800 tracking-tight">Sesi Akademik Belum Aktif</h3>
        <p class="text-amber-600 font-medium max-w-lg mx-auto mt-2">Daftar Rombel akan muncul otomatis setelah Anda mengaktifkan Tahun Pelajaran dan mengimport data siswa.</p>
    </div>
    @else
    <div class="flex justify-between items-center mb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Rombel</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Sesi Aktif: <span class="text-sky-600 font-bold italic">{{ $tahunAktif->tahun }} - {{ $tahunAktif->semester }}</span></p>
        </div>
    </div>

    @if($rombels->isEmpty())
    <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-sm">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Belum Ada Data Rombel</h3>
        <p class="text-slate-500 mt-1">Silakan lakukan import data siswa untuk membuat data rombel secara otomatis.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rombels as $rombel)
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <!-- Decorative background element -->
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-sky-100 text-sky-600 rounded-2xl group-hover:bg-sky-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-black text-slate-800 group-hover:text-sky-700 transition-colors">{{ $rombel->nama }}</h3>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-2xl font-bold text-slate-700">{{ $rombel->siswas_count }}</span>
                    <span class="text-sm font-medium text-slate-400">Siswa Terdaftar</span>
                </div>
                
                <div class="mt-auto pt-6">
                    <a href="{{ route('rombels.show', $rombel->id) }}" class="inline-flex items-center justify-center gap-2 w-full bg-slate-50 hover:bg-sky-600 hover:text-white text-slate-600 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border border-slate-100 hover:border-sky-600">
                        Lihat Anggota
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endif
</div>
@endsection
