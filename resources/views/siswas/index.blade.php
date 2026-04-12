@extends('layouts.app')

@section('title', 'Data Pokok Siswa')
@section('header_title', 'Data Pokok Siswa')
@section('breadcrumb', 'Data Pokok Siswa')

@section('content')
<div x-data="{ importModal: false, masterImportModal: false, guideModal: false, fileName: '', fileSelected: false, loading: false }">
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
            <a href="{{ route('siswas.promote.index') }}" 
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all shadow-indigo-600/20 hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Naik Kelas
            </a>
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
                    <button @click="open = false; importModal = true; fileName = ''; fileSelected = false" 
                            class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        </div>
                        Format Dapodik
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
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-indigo-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="masterImportModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/20">
            
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-8 text-white relative">
                <button @click="masterImportModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-2xl font-extrabold tracking-tight">Master Import Buku Induk</h3>
                <p class="text-indigo-100 text-sm mt-1 font-medium italic opacity-90">Sangat disarankan untuk input data manual secara massal.</p>
            </div>

            <form action="{{ route('siswas.master-import') }}" method="POST" enctype="multipart/form-data" class="p-8" @submit="loading = true">
                @csrf
                <div class="space-y-6">
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl">
                        <div class="flex gap-4 items-start mb-3">
                            <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="text-sm text-indigo-800 font-medium italic leading-relaxed">
                                Gunakan fitur ini untuk melakukan pemutakhiran data secara menyeluruh (Buku Induk + Data Siswa). 
                            </div>
                        </div>
                        <a href="{{ asset('templates/master_buku_induk_template.xlsx') }}" class="inline-flex items-center gap-2 text-xs font-black text-indigo-700 hover:text-indigo-900 bg-white px-4 py-2 rounded-xl shadow-sm border border-indigo-100 transition-all hover:translate-x-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Template Excel
                        </a>
                    </div>

                    <div class="relative group" x-data="{ localFileName: '', localFileSelected: false }">
                        <label for="master_excel_file" class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wide">Select Template File</label>
                        
                        <div class="border-2 border-dashed border-slate-200 group-hover:border-indigo-500 rounded-2xl p-8 transition-all bg-slate-50/50 group-hover:bg-white flex flex-col items-center justify-center gap-3 relative overflow-hidden" 
                             :class="{ 'border-indigo-500 bg-indigo-50/20': localFileSelected }">
                            
                            <input type="file" name="file" id="master_excel_file" required x-ref="masterRef"
                                   class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                   @change="if($event.target.files[0]) { localFileName = $event.target.files[0].name; localFileSelected = true }">
                            
                            <div x-show="!localFileSelected" class="flex flex-col items-center pointer-events-none">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-1">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-500 group-hover:text-indigo-700 text-center">Drag filled template here</span>
                            </div>
                            
                            <div x-show="localFileSelected" class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center shadow-sm mb-1 animate-pulse">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-800 px-4 break-all" x-text="localFileName"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="masterImportModal = false" :disabled="loading" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-all">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading" class="flex-[2] py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span>Proses Import Master</span>
                        </template>
                        <template x-if="loading">
                            <span>Memproses...</span>
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
                        <p class="text-sky-100 text-sm mt-0.5 font-medium">Informasi & Cara Mengelola Data Pokok Siswa</p>
                    </div>
                </div>
            </div>

            <div class="p-8 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6 text-slate-600 text-sm leading-relaxed">
                    
                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">1</span>
                            Import Data Dapodik
                        </h4>
                        <p class="ml-8">Gunakan tombol <span class="font-bold text-emerald-600">Import Dapodik</span> untuk memasukkan banyak data siswa sekaligus menggunakan file Excel yang di-export langsung dari aplikasi Dapodik sekolah.</p>
                        <p class="ml-8 mt-1 text-xs text-emerald-700 font-semibold bg-emerald-50 inline-block px-3 py-1.5 rounded-lg border border-emerald-100 mt-2">Penting: Di dalam file Excel, urutan data siswa harus dimulai pada baris ke-7 dan nama berada di kolom B.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">2</span>
                            Syarat Tahun Pelajaran Aktif
                        </h4>
                        <p class="ml-8">Untuk menghindari kesalahan data, tombol Import hanya akan aktif jika terdapat <span class="font-bold text-sky-600">Tahun Pelajaran yang diset sebagai "Aktif"</span>. Pastikan Anda telah mengonfigurasinya terlebih dahulu.</p>
                    </div>

                    <div>
                        <h4 class="text-slate-800 font-bold text-base mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs">3</span>
                            Filter Data (Dropdown)
                        </h4>
                        <p class="ml-8">Gunakan menu dropdown di bagian atas untuk menyaring data berdasarkan <span class="font-bold text-slate-700">Status</span>, <span class="font-bold text-slate-700">Tingkat Kelas</span>, atau <span class="font-bold text-slate-700">Rombongan Belajar (Rombel)</span>. Halaman akan otomatis memuat data setiap kali Anda mengubah pilihan.</p>
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
