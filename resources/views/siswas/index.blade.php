@extends('layouts.app')

@section('title', 'Data Pokok Siswa')
@section('header_title', 'Data Pokok Siswa')
@section('breadcrumb', 'Data Pokok Siswa')

@section('content')
<div x-data="{ 
    importModal: false, 
    masterImportModal: false, 
    guideModal: false, 
    fileName: '', 
    fileSelected: false, 
    loading: false,
    isMax: false,
    posX: 0,
    posY: 0,
    dragging: false,
    startX: 0,
    startY: 0,
    startDrag(e) {
        if(this.isMax) return;
        this.dragging = true;
        this.startX = e.clientX - this.posX;
        this.startY = e.clientY - this.posY;
    },
    doDrag(e) {
        if(!this.dragging) return;
        this.posX = e.clientX - this.startX;
        this.posY = e.clientY - this.startY;
    },
    stopDrag() {
        this.dragging = false;
    }
}" @mousemove.window="doDrag" @mouseup.window="stopDrag">
    @if(!$tahunAktif)
    <div class="mb-8 bg-rose-50 border-2 border-rose-200 border-dashed rounded-3xl p-8 text-center shadow-sm">
        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-black text-rose-800 tracking-tight">Perhatian: Sesi Akademik Belum Aktif!</h3>
        <p class="text-rose-600 font-medium max-w-lg mx-auto mt-2">Anda wajib menambahkan atau mengaktifkan Tahun Pelajaran terlebih dahulu sebelum dapat mengelola Data Pokok Siswa.</p>
        <div class="mt-6">
            <a href="{{ route('tahun-pelajaran.index') }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-lg shadow-rose-200 hover:-translate-y-0.5">
                Konfigurasi Tahun Pelajaran
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
    @endif
    {{-- Filters Card --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('siswas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            {{-- Search Search --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Siswa</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="pl-10 pr-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all w-full shadow-inner font-bold text-slate-700" 
                        placeholder="Nama, NIS, atau NISN...">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            {{-- Filter Tingkat --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tingkat Kelas</label>
                <select name="tingkat" onchange="this.form.submit()" 
                        class="w-full pl-4 pr-10 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner font-bold text-slate-700">
                    <option value="">Semua Tingkat</option>
                    @foreach([1, 2, 3, 4, 5, 6] as $t)
                        <option value="{{ $t }}" {{ $tingkat == $t ? 'selected' : '' }}>Tingkat {{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Rombel --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rombongan Belajar</label>
                <select name="rombel" onchange="this.form.submit()" 
                        class="w-full pl-4 pr-10 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner font-bold text-slate-700">
                    <option value="">Semua Rombel</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r }}" {{ $rombel == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md shadow-indigo-200 font-bold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>

                @if(request()->anyFilled(['search', 'tingkat', 'rombel']))
                    <a href="{{ route('siswas.index') }}" 
                       class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all shadow-sm flex items-center justify-center" 
                       title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="mb-6 flex justify-between items-center px-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Siswa <span class="text-slate-400 font-medium ml-1">({{ $status }})</span></h2>
            <p class="text-sm font-medium text-slate-500 mt-1">
                @if($tahunAktif)
                    Menampilkan data siswa untuk sesi <span class="text-sky-600 font-bold italic">{{ $tahunAktif->tahun }} - {{ $tahunAktif->semester }}</span>.
                @else
                    Silakan aktifkan tahun pelajaran untuk melihat data.
                @endif
            </p>
        </div>
        
        @hasanyrole('Super Admin|Operator|Tata Usaha')
        <div class="flex gap-3">
            @if($canPromote)
            <button 
               @click="$dispatch('open-promote-siswa-modal')"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all shadow-indigo-600/20 hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Naikan Semester
            </button>
            @endif
            @if($canPromoteGrade)
            <button 
               @click="$dispatch('open-promote-grade-modal')"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all shadow-amber-500/20 hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                Naikan Kelas
            </button>
            @endif
            <div class="h-10 w-px bg-slate-200 mx-1"></div>
            <button 
                @click="guideModal = true"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-sky-600 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-slate-100 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan
            </button>
            <div class="relative group" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all shadow-emerald-600/20 hover:shadow-md cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Data
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden">
                    <button @click="open = false; masterImportModal = true; fileName = ''; fileSelected = false" 
                            class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        Master Buku Induk
                    </button>
                    <button disabled 
                            class="w-full text-left px-4 py-3 text-sm font-bold text-slate-400 bg-slate-50/30 cursor-not-allowed flex items-center gap-3 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="leading-tight">Format Dapodik</span>
                            <span class="text-[10px] text-rose-500 font-medium italic">Non-aktif Sementara</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        @endhasanyrole
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-bold uppercase tracking-tight">Terjadi Kesalahan Validasi:</p>
        </div>
        <ul class="list-disc list-inside text-xs font-medium space-y-1 ml-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-xs font-extrabold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Nama Lengkap / NISN</th>
                        <th class="py-4 px-6">Jenjang / Rombel</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Tempat, Tanggal Lahir</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    {{ substr($siswa->nama, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-base leading-tight">{{ $siswa->nama }}</p>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $siswa->nisn ?? 'NISN Kosong' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700 italic">
                                    {{ $siswa->tingkat_kelas ? 'Tingkat ' . $siswa->tingkat_kelas : '-' }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium tracking-tight">
                                    {{ $siswa->rombel ? $siswa->rombel->nama : ($siswa->rombel_saat_ini ?? 'Tanpa Rombel') }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusColor = match($siswa->status) {
                                    'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'Lulus' => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'Keluar/Mutasi' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    default => 'bg-slate-50 text-slate-700 border-slate-100'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ $siswa->status ?? 'Aktif' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-500 font-medium">
                            {{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('siswas.show', $siswa) }}" class="p-2 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all" title="Detail">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @hasanyrole('Super Admin|Operator|Tata Usaha')
                                {{-- <a href="{{ route('siswas.edit', $siswa) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a> --}}
                                @endhasanyrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="font-bold text-lg text-slate-600">Belum Ada Data Siswa</p>
                                <p class="text-sm">Gunakan tombol "Import Dapodik" untuk memuat data.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siswas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>

    <!-- Master Import Modal -->
    <div x-show="masterImportModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-indigo-950/60 backdrop-blur-sm" x-cloak>
        
        <div :class="{
                'w-full h-full max-w-none max-h-none rounded-none m-0': isMax,
                'w-full max-w-lg max-h-[90vh] rounded-3xl': !isMax,
                'transition-all duration-300': !dragging 
             }" 
             :style="(!isMax && posX !== undefined) ? `transform: translate(${posX}px, ${posY}px)` : ''"
             class="bg-white shadow-2xl overflow-hidden border border-white/20 flex flex-col transform transition-all">
            
            {{-- Header Draggable --}}
            <div @mousedown="startDrag($event)" 
                 class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-6 text-white relative flex items-center justify-between shrink-0 cursor-move select-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Import Master Buku Induk</h3>
                        <p class="text-indigo-100 text-xs font-medium opacity-80 italic">Drag header untuk menggeser posisi</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-1">
                    <button type="button" @click="isMax = !isMax; if(!isMax) { posX = 0; posY = 0; }" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg x-show="!isMax" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isMax" class="w-4 h-4" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4v4m0-4l-5 5m11-5h4v4m0-4l5 5M4 10V6h4m-4 0l5 5m11 5V6h-4m4 0l-5 5"/></svg>
                    </button>
                    <button @click="masterImportModal = false" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('siswas.master-import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden" @submit="loading = true">
                @csrf
                <div class="p-8 space-y-6 overflow-y-auto flex-1">
                    <div class="p-5 bg-indigo-50 border border-indigo-100 rounded-2xl">
                        <div class="flex gap-4 items-start mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="text-sm text-indigo-900 font-bold italic leading-relaxed">
                                Fitur ini memproses pemutakhiran data secara menyeluruh (Buku Induk + Data Pokok Siswa). Data yang sudah tersedia pada tabel akan diperbarui secara otomatis jika terdapat dalam berkas Excel yang diunggah. Mohon teliti sebelum melanjutkan proses.
                            </div>
                        </div>
                        <a href="{{ asset('templates/master_buku_induk_template.xlsx') }}" class="inline-flex items-center gap-2 text-xs font-black text-indigo-700 hover:text-indigo-900 bg-white px-5 py-2.5 rounded-xl shadow-sm border border-indigo-100 transition-all hover:translate-x-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Template Excel Baru
                        </a>
                    </div>

                    <div class="relative group" x-data="{ localFileName: '', localFileSelected: false }">
                        <label for="master_excel_file" class="block text-xs font-black text-slate-400 mb-3 ml-1 uppercase tracking-widest">Pilih Berkas Excel</label>
                        
                        <div class="border-2 border-dashed border-slate-200 group-hover:border-indigo-500 rounded-3xl p-10 transition-all bg-slate-50/50 group-hover:bg-white flex flex-col items-center justify-center gap-3 relative overflow-hidden" 
                             :class="{ 'border-indigo-500 bg-indigo-50/20': localFileSelected }">
                            
                            <input type="file" name="file" id="master_excel_file" required x-ref="masterRef"
                                   class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                   @change="if($event.target.files[0]) { localFileName = $event.target.files[0].name; localFileSelected = true }">
                            
                            <div x-show="!localFileSelected" class="flex flex-col items-center pointer-events-none text-center">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-500 group-hover:text-indigo-700">Tarik berkas atau klik di sini</span>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">Format: .xlsx (Excel)</p>
                            </div>
                            
                            <div x-show="localFileSelected" class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200 mb-2 animate-bounce">
                                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-800 px-4 break-all" x-text="localFileName"></span>
                                <p class="text-[10px] text-indigo-500 font-bold mt-1 uppercase">Berkas Siap Diproses</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex gap-3 shrink-0">
                    <button type="button" @click="masterImportModal = false" :disabled="loading" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-wait">
                        <template x-if="!loading">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Mulai Import Master</span>
                            </div>
                        </template>
                        <template x-if="loading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses Data...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dapodik Import Modal -->
    <div x-show="importModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-emerald-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="importModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/20">
            
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8 text-white relative">
                <button @click="importModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5m14 0h-1a4 4 0 00-4 4v2m-3-1l-3-3m0 0l3-3m-3 3h8m4 3a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2v1m14 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v1"/></svg>
                </div>
                <h3 class="text-2xl font-extrabold tracking-tight">Import Data Dapodik</h3>
                <p class="text-emerald-100 text-sm mt-1 font-medium italic opacity-90">Gunakan file excel asli dari aplikasi Dapodik.</p>
            </div>

            <form action="{{ route('siswas.import') }}" method="POST" enctype="multipart/form-data" class="p-8" @submit="loading = true">
                @csrf
                <div class="space-y-6">
                    <div class="p-4 bg-sky-50 border border-sky-100 rounded-2xl flex gap-4 items-start">
                        <svg class="w-5 h-5 text-sky-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm text-sky-800 font-medium">
                            Pastikan data pada file Excel Anda dimulai pada <strong>baris ke-7</strong> dan kolom data pertama adalah <strong>Kolom B (Nama)</strong>.
                        </div>
                    </div>

                    <div class="relative group">
                        <label for="excel_file" class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wide">Pilih File Excel (.xlsx / .xls)</label>
                        
                        <div class="border-2 border-dashed border-slate-200 group-hover:border-emerald-500 rounded-2xl p-8 transition-all bg-slate-50/50 group-hover:bg-white flex flex-col items-center justify-center gap-3 relative overflow-hidden" 
                             :class="{ 'border-emerald-500 bg-emerald-50/20': fileSelected }">
                            
                            <input type="file" name="file" id="excel_file" required x-ref="fileInput"
                                   class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                   @change="if($event.target.files[0]) { fileName = $event.target.files[0].name; fileSelected = true }">
                            
                            <div x-show="!fileSelected" class="flex flex-col items-center pointer-events-none">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-1">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-500 group-hover:text-emerald-700">Klik atau seret file ke sini</span>
                            </div>
                            
                            <div x-show="fileSelected" class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center shadow-sm mb-1 animate-pulse">
                                    <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-800 px-4 break-all" x-text="fileName"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="importModal = false" :disabled="loading" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-all">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading" class="flex-[2] py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer disabled:bg-slate-400 disabled:shadow-none flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span>Mulai Import Data</span>
                        </template>
                        <template x-if="loading">
                            <span>Memproses...</span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                        <h3 class="text-xl font-extrabold tracking-tight">Panduan Penggunaan</h3>
                        <p class="text-sky-100 text-sm mt-0.5 font-medium">Import, Naikan Semester, Naikan Kelas & Filter Data Siswa</p>
                    </div>
                </div>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6 text-slate-600 text-sm leading-relaxed">
                    
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                        <h4 class="text-indigo-800 font-bold text-base mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Penjelasan Umum
                        </h4>
                        <p class="text-indigo-700 text-sm">Halaman <strong>Data Pokok Siswa</strong> adalah pusat pengelolaan biodata dan riwayat akademik seluruh siswa. Anda dapat mengimpor data, memutasi siswa (kenaikan kelas/semester), serta memfilter dan melihat detail buku induk setiap siswa.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">1</span>
                                Import Data Massal
                            </h4>
                            <p class="text-slate-600 ml-8 mb-2">Tombol <strong>Import Data</strong> memungkinkan Anda memasukkan banyak data sekaligus melalui Excel.</p>
                            <ul class="ml-8 space-y-1.5 list-disc pl-4 text-xs text-slate-500">
                                <li><strong>Master Buku Induk:</strong> Untuk sinkronisasi data yang sangat lengkap sesuai format spesifik buku induk.</li>
                                <li><strong>Format Dapodik:</strong> Untuk mengambil data mentah dari Dapodik (pastikan isi data nama ada di kolom B mulai baris ke 7).</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">2</span>
                                Naikan Semester
                            </h4>
                            <p class="text-slate-600 ml-8">Gunakan opsi <strong>Naikan Semester</strong> di awal semester baru (genap). Sistem akan menyalin secara otomatis data seluruh siswa aktif dari semester sebelumnya tanpa perlu mengimport ulang.</p>
                        </div>

                        <div>
                            <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs">3</span>
                                Naikan Kelas &amp; Lulus
                            </h4>
                            <p class="text-slate-600 ml-8 mb-2">Opsi <strong>Naikan Kelas</strong> dijalankan saat akhir tahun akademik. Siswa akan otomatis ditingkatkan kelasnya menuju tahun pelajaran baru, dan siswa kelas tingkat akhir akan berstatus Lulus.</p>
                        </div>

                        <div>
                            <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">4</span>
                                Filter &amp; Pencarian
                            </h4>
                            <p class="text-slate-600 ml-8">Gunakan kotak pencarian berdasarkan Nama, NIS, maupun NISN. Anda juga dapat menyaring menggunakan dropdown spesifik untuk tingkat kelas dan rombongan belajar.</p>
                        </div>
                    </div>

                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 mt-4 flex gap-3 items-start">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <p class="text-rose-700 text-xs leading-relaxed"><strong>Penting Diperhatikan:</strong> Semua fitur mutasi dan penambahan (import) mewajibkan Anda sudah mengkonfigurasi <strong>Sesi Tahun Pelajaran Aktif</strong>. Hal ini meminimalisir salah input memori antar dua jenjang semester atau generasi angkatan.</p>
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
    {{-- MODAL: Naikan Semester (Siswa) --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    @if($canPromote && $previousGenapId && $semesterSumber)
    <div x-data="{ 
            open: false,
            isMax: false,
            posX: 0,
            posY: 0,
            dragging: false,
            startX: 0,
            startY: 0,
            loading: false,
            selectedYearId: '{{ $previousGenapId ?? '' }}',
            siswas: [],
            init() { },
            fetchPreview() {
                if (!this.selectedYearId) {
                    this.siswas = [];
                    return;
                }
                this.loading = true;
                fetch(`/api/siswas/preview/${this.selectedYearId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.siswas = data;
                        this.loading = false;
                    });
            },
            confirmPromote() {
                const yearName = '{{ $semesterSumber->tahun }} - {{ $semesterSumber->semester }}';
                
                Swal.fire({
                    title: 'Pindahkan Semester?',
                    text: 'Akan menyalin ' + this.siswas.length + ' data siswa dari semester (' + yearName + ') ke dalam semester aktif saat ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#f43f5e',
                    confirmButtonText: 'Ya, Salin Data!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.promoteForm.submit();
                    }
                });
            },
            startDrag(e) {
                if(this.isMax) return;
                this.dragging = true;
                this.startX = e.clientX - this.posX;
                this.startY = e.clientY - this.posY;
            },
            doDrag(e) {
                if(!this.dragging) return;
                this.posX = e.clientX - this.startX;
                this.posY = e.clientY - this.startY;
            },
            stopDrag() {
                this.dragging = false;
            }
         }" 
         @open-promote-siswa-modal.window="open = true; siswas = []; fetchPreview()"
         @mousemove.window="doDrag" 
         @mouseup.window="stopDrag"
         x-show="open" x-transition 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div :class="{
                'w-full h-full max-w-none max-h-none rounded-none m-0': isMax,
                'w-full max-w-5xl max-h-[90vh] rounded-3xl': !isMax,
                'transition-all duration-300': !dragging 
             }" 
             :style="(!isMax && posX !== undefined) ? `transform: translate(${posX}px, ${posY}px)` : ''"
             class="bg-white shadow-2xl overflow-hidden flex flex-col border border-white/20">

            <div @mousedown="startDrag($event)" class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-8 py-5 text-white flex items-center justify-between shrink-0 cursor-move select-none">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Naikan Semester</h3>
                        <p class="text-indigo-100 text-xs font-medium mt-0.5">Salin data siswa dari semester/tahun sebelumnya.</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" @click="isMax = !isMax; if(!isMax) { posX = 0; posY = 0; }" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg x-show="!isMax" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isMax" class="w-4 h-4" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4v4m0-4l-5 5m11-5h4v4m0-4l5 5M4 10V6h4m-4 0l5 5m11 5V6h-4m4 0l-5 5"/></svg>
                    </button>
                    <button type="button" @click="open = false" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-8 flex-1 overflow-y-auto space-y-8">
                <form x-ref="promoteForm" action="{{ route('siswas.promote-semester') }}" method="POST">
                    @csrf
                    <input type="hidden" name="source_tahun_id" value="{{ $previousGenapId }}">
                    <div class="max-w-xl">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sumber Semester</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 shadow-inner">
                            <p class="text-sm font-black text-slate-700">{{ $semesterSumber?->tahun }} — Semester {{ $semesterSumber?->semester }}</p>
                            <p class="text-xs text-slate-400 mt-1">Semua siswa aktif dari semester ini akan disalin ke semester aktif</p>
                        </div>
                    </div>
                </form>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Preview Data Siswa
                        </h4>
                        <span x-show="siswas.length > 0" class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg border border-indigo-100 uppercase" x-text="siswas.length + ' Siswa ditemukan'"></span>
                    </div>

                    <div class="border-2 border-dashed border-slate-200 rounded-3xl overflow-hidden min-h-[200px] flex flex-col">
                        <div x-show="loading" class="flex-1 flex flex-col items-center justify-center py-12" x-cloak>
                            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-sm font-bold text-slate-400">Mengambil data...</p>
                        </div>

                        <div x-show="!loading && siswas.length === 0" class="flex-1 flex flex-col items-center justify-center py-12 text-slate-300" x-cloak>
                            <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="text-sm font-bold text-slate-400">Tidak ada siswa aktif di semester sumber</p>
                        </div>

                        <table x-show="!loading && siswas.length > 0" class="w-full text-left border-collapse" x-cloak>
                            <thead class="bg-slate-50">
                                <tr class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 w-16 text-center">No</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">NISN</th>
                                    <th class="px-6 py-4 text-right">Rombel Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <template x-for="(s, index) in siswas" :key="index">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-400" x-text="index + 1"></td>
                                        <td class="px-6 py-4 text-sm font-bold text-slate-700" x-text="s.nama"></td>
                                        <td class="px-6 py-4 text-xs text-slate-500 font-medium" x-text="s.nisn || '-'"></td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-md" x-text="s.rombel_saat_ini || '-'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end shrink-0 gap-3">
                <button type="button" @click="open = false" class="px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-2xl transition-colors cursor-pointer">Batal</button>
                <button type="button" @click="confirmPromote()" :disabled="!selectedYearId || loading || siswas.length === 0"
                        :class="(!selectedYearId || loading || siswas.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700 hover:-translate-y-0.5 shadow-indigo-200'"
                        class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl shadow-lg transition-all cursor-pointer flex items-center gap-2">
                    Salin Data Siswa
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endif
    @endhasanyrole

    {{-- MODAL: Naikan Kelas (Grade Promotion) --}}
    @hasanyrole('Super Admin|Operator|Tata Usaha')
    @if($canPromoteGrade && $previousGenapId)
    <div x-data="{ 
            open: false,
            isMax: false,
            posX: 0,
            posY: 0,
            dragging: false,
            startX: 0,
            startY: 0,
            loading: false,
            siswas: [],
            summary: { total: 0, naik: 0, lulus: 0 },
            sourceId: '{{ $previousGenapId }}',
            init() {
                // auto-fetch on open
            },
            fetchPreview() {
                this.loading = true;
                fetch(`/api/siswas/grade-preview/${this.sourceId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.siswas = data.siswas;
                        this.summary = data.summary;
                        this.loading = false;
                    });
            },
            confirmPromote() {
                var naik = this.summary.naik;
                var lulus = this.summary.lulus;
                var htmlContent = '<div class=&quot;text-left text-sm space-y-2&quot;>' +
                    '<p><strong>' + naik + '</strong> siswa akan <span style=&quot;color:#059669;font-weight:bold&quot;>naik kelas</span></p>' +
                    '<p><strong>' + lulus + '</strong> siswa akan dinyatakan <span style=&quot;color:#e11d48;font-weight:bold&quot;>LULUS</span></p>' +
                    '<p style=&quot;font-size:0.75rem;color:#94a3b8;margin-top:0.75rem&quot;>Siswa lulus tidak akan muncul lagi di daftar siswa.</p>' +
                    '</div>';
                Swal.fire({
                    title: 'Proses Naik Kelas?',
                    html: htmlContent,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#f43f5e',
                    confirmButtonText: 'Ya, Proses Naik Kelas!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.gradeForm.submit();
                    }
                });
            },
            startDrag(e) {
                if(this.isMax) return;
                this.dragging = true;
                this.startX = e.clientX - this.posX;
                this.startY = e.clientY - this.posY;
            },
            doDrag(e) {
                if(!this.dragging) return;
                this.posX = e.clientX - this.startX;
                this.posY = e.clientY - this.startY;
            },
            stopDrag() {
                this.dragging = false;
            }
         }" 
         @open-promote-grade-modal.window="open = true; fetchPreview()"
         @mousemove.window="doDrag" 
         @mouseup.window="stopDrag"
         x-show="open" x-transition 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div :class="{
                'w-full h-full max-w-none max-h-none rounded-none m-0': isMax,
                'w-full max-w-5xl max-h-[90vh] rounded-3xl': !isMax,
                'transition-all duration-300': !dragging 
             }" 
             :style="(!isMax && posX !== undefined) ? `transform: translate(${posX}px, ${posY}px)` : ''"
             class="bg-white shadow-2xl overflow-hidden flex flex-col border border-white/20">

            {{-- Header --}}
            <div @mousedown="startDrag($event)" class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-5 text-white flex items-center justify-between shrink-0 cursor-move select-none">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Naikan Kelas</h3>
                        <p class="text-amber-100 text-xs font-medium mt-0.5">Promosi siswa ke tingkat berikutnya & kelulusan tingkat akhir.</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" @click="isMax = !isMax; if(!isMax) { posX = 0; posY = 0; }" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg x-show="!isMax" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isMax" class="w-4 h-4" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4v4m0-4l-5 5m11-5h4v4m0-4l5 5M4 10V6h4m-4 0l5 5m11 5V6h-4m4 0l-5 5"/></svg>
                    </button>
                    <button type="button" @click="open = false" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-8 flex-1 overflow-y-auto space-y-6">
                <form x-ref="gradeForm" action="{{ route('siswas.promote-grade') }}" method="POST">
                    @csrf
                    <input type="hidden" name="source_tahun_id" :value="sourceId">
                </form>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                        <p class="text-3xl font-black text-slate-700" x-text="summary.total">0</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Siswa</p>
                    </div>
                    <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 text-center">
                        <p class="text-3xl font-black text-emerald-600" x-text="summary.naik">0</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mt-1">Naik Kelas</p>
                    </div>
                    <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 text-center">
                        <p class="text-3xl font-black text-rose-600" x-text="summary.lulus">0</p>
                        <p class="text-xs font-bold text-rose-500 uppercase tracking-wider mt-1">Lulus</p>
                    </div>
                </div>

                {{-- Preview Table --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Preview Data Naik Kelas
                        </h4>
                    </div>

                    <div class="border-2 border-dashed border-slate-200 rounded-3xl overflow-hidden min-h-[200px] flex flex-col">
                        <div x-show="loading" class="flex-1 flex flex-col items-center justify-center py-12" x-cloak>
                            <div class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-sm font-bold text-slate-400">Mengambil data...</p>
                        </div>

                        <div x-show="!loading && siswas.length === 0" class="flex-1 flex flex-col items-center justify-center py-12 text-slate-300" x-cloak>
                            <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="text-sm font-bold text-slate-400">Tidak ada data siswa untuk ditampilkan</p>
                        </div>

                        <table x-show="!loading && siswas.length > 0" class="w-full text-left border-collapse" x-cloak>
                            <thead class="bg-slate-50">
                                <tr class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 w-16 text-center">No</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">NISN</th>
                                    <th class="px-6 py-4 text-center">Tingkat Saat Ini</th>
                                    <th class="px-6 py-4 text-center">Tingkat Baru</th>
                                    <th class="px-6 py-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <template x-for="(s, index) in siswas" :key="index">
                                    <tr class="hover:bg-slate-50/50 transition-colors" :class="s.will_graduate ? 'bg-rose-50/30' : ''">
                                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-400" x-text="index + 1"></td>
                                        <td class="px-6 py-4 text-sm font-bold text-slate-700" x-text="s.nama"></td>
                                        <td class="px-6 py-4 text-xs text-slate-500 font-medium" x-text="s.nisn || '-'"></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 text-sm font-black" x-text="s.tingkat_kelas || '-'"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <template x-if="!s.will_graduate">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 text-sm font-black" x-text="s.tingkat_baru || '-'"></span>
                                                </div>
                                            </template>
                                            <template x-if="s.will_graduate">
                                                <span class="text-xs text-rose-400 font-bold">—</span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <template x-if="!s.will_graduate">
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-full border border-emerald-100">Naik Kelas</span>
                                            </template>
                                            <template x-if="s.will_graduate">
                                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 text-[10px] font-black rounded-full border border-rose-200 animate-pulse">🎓 LULUS</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5 text-emerald-600 font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span x-text="summary.naik + ' Naik Kelas'"></span>
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-600 font-bold">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span x-text="summary.lulus + ' Lulus'"></span>
                    </span>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="open = false" class="px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-2xl transition-colors cursor-pointer">Batal</button>
                    <button type="button" @click="confirmPromote()" :disabled="loading || siswas.length === 0"
                            :class="(loading || siswas.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-amber-600 hover:-translate-y-0.5 shadow-amber-200'"
                            class="px-8 py-3 bg-amber-500 text-white text-sm font-bold rounded-2xl shadow-lg transition-all cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        Proses Naik Kelas
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endhasanyrole
@endsection

