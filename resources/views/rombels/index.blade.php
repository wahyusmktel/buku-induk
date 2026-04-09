@extends('layouts.app')

@section('title', 'Daftar Rombel')
@section('header_title', 'Rombongan Belajar')
@section('breadcrumb', 'Daftar Rombel')

@section('content')
<div class="space-y-6" x-data="{ guideModal: false }">
    @if(!$tahunAktif)
    <div class="bg-amber-50 border-2 border-amber-200 border-dashed rounded-3xl p-8 text-center shadow-sm">
        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-black text-amber-800 tracking-tight">Sesi Akademik Belum Aktif</h3>
        <p class="text-amber-600 font-medium max-w-lg mx-auto mt-2">Daftar Rombel akan muncul otomatis setelah Anda mengaktifkan Tahun Pelajaran dan mengimport data siswa.</p>
    </div>
    @else
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Rombel</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Sesi Aktif: <span class="text-sky-600 font-bold italic">{{ $tahunAktif->tahun }} - {{ $tahunAktif->semester }}</span></p>
        </div>
        <button 
            @click="guideModal = true"
            class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-sky-600 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-slate-100 cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Panduan
        </button>
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
                
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-xl font-black text-slate-800 group-hover:text-sky-700 transition-colors">{{ $rombel->nama }}</h3>
                    @if($rombel->tingkat)
                        <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-lg uppercase tracking-wider shadow-sm border border-amber-200/50">Tingkat {{ $rombel->tingkat }}</span>
                    @endif
                </div>
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

    <!-- Guide Modal -->
    <div x-show="guideModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="guideModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-white/20">
            
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-6 text-white relative">
                <button @click="guideModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Panduan Daftar Rombel</h3>
                        <p class="text-sky-100 text-sm mt-0.5 font-medium">Informasi & Cara Kerja Halaman Rombongan Belajar</p>
                    </div>
                </div>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6 text-slate-600 text-sm leading-relaxed">
                    
                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">1</span>
                            Data Terbuat Secara Otomatis
                        </h4>
                        <p class="ml-8">Berbeda dengan halaman lain, Anda <span class="font-bold text-slate-700">tidak perlu membuat data rombel secara manual</span>. Sistem akan otomatis mendeteksi rombel siswa berdasarkan data yang Anda import melalui menu "Data Pokok Siswa".</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">2</span>
                            Melihat Anggota Rombel
                        </h4>
                        <p class="ml-8">Setiap kartu rombel yang tampil mewakili satu kelas. Klik tombol <span class="font-bold text-sky-600">Lihat Anggota</span> pada kartu tersebut untuk masuk ke daftar spesifik yang menampilkan siapa saja siswa di dalam rombel tersebut beserta nilai prestasinya.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs">3</span>
                            Pengaruh Tahun Pelajaran
                        </h4>
                        <p class="ml-8">Data rombel terhubung erat dengan <span class="font-bold text-amber-600">Tahun Pelajaran Aktif</span>. Setiap pergantian semster/tahun, data rombel otomatis berpindah sesuai data Dapodik terbaru yang Anda import.</p>
                    </div>

                </div>
            </div>

            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="guideModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-200 transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
