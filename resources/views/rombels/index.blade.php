@extends('layouts.app')

@section('title', 'Daftar Rombel')
@section('header_title', 'Rombongan Belajar')
@section('breadcrumb', 'Daftar Rombel')

@section('content')
<div class="space-y-6" x-data="{ 
    guideModal: false, 
    addModal: {{ $errors->any() ? 'true' : 'false' }},
    jenjang: '{{ $jenjang }}',
    tingkatOptions: [],
    init() {
        if (this.jenjang === 'SD') {
            this.tingkatOptions = [1,2,3,4,5,6];
        } else if (this.jenjang === 'SMP') {
            this.tingkatOptions = [7,8,9];
        } else if (this.jenjang === 'SMA/SMK') {
            this.tingkatOptions = [10,11,12];
        } else {
            this.tingkatOptions = [1,2,3,4,5,6,7,8,9,10,11,12];
        }
    }
}">
    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-medium text-sm flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 font-medium text-sm flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('error') }}
    </div>
    @endif
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
        <div class="flex items-center gap-2">
            <button 
                @click="addModal = true"
                class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-sky-500/20 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Rombel / Kelas
            </button>
            <button 
                @click="guideModal = true"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-sky-600 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-slate-100 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan
            </button>
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
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider">Info Rombel</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider">Detail Pendidikan</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider">Wali Kelas</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider text-center">Siswa</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rombels as $rombel)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base group-hover:text-sky-700 transition-colors">{{ $rombel->nama }}</h3>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="inline-flex text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 uppercase tracking-wider border border-slate-200">
                                            {{ $rombel->jenis_rombel ?: 'Kelas' }}
                                        </span>
                                        @if($rombel->tingkat)
                                        <span class="inline-flex text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 uppercase tracking-wider border border-amber-200/50">
                                            Tingkat {{ $rombel->tingkat }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 block sm:table-cell">
                            <p class="text-sm font-semibold text-slate-700">{{ $rombel->kompetensi_keahlian ?: 'Umum / Nasional' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $rombel->kurikulum ?: 'Kurikulum tidak diatur' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            @if($rombel->guru_id)
                                <p class="text-sm font-semibold text-slate-700">Guru Terpilih</p>
                            @else
                                <span class="inline-flex text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-500 rounded-lg">Belum Diatur</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center justify-center">
                                <span class="text-lg font-black text-slate-800">{{ $rombel->siswas_count }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Terdaftar</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('rombels.show', $rombel->id) }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-sky-50 text-sky-600 px-4 py-2 rounded-xl text-xs font-bold transition-all border border-slate-200 hover:border-sky-200 shadow-sm">
                                Lihat Anggota
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                            Pembuatan Data Rombel
                        </h4>
                        <p class="ml-8">Sistem memungkinkan dua acara pembentukan rombel. Pertama, otomatis terdeteksi jika Anda mengimport "Data Pokok Siswa" dengan keterangan kelas. Kedua, Anda dapat membuat <span class="font-bold text-slate-700">Rombel secara manual melalui tombol "Tambah Rombel / Kelas"</span>.</p>
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

    <!-- Add Rombel Modal -->
    <div x-show="addModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="addModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-white/20 flex flex-col max-h-[90vh]">
            
            <div class="bg-sky-600 px-6 py-5 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold tracking-tight">Tambah Rombel / Kelas</h3>
                        <p class="text-sky-100 text-xs font-medium">Buat master rombongan belajar baru ({{ $tahunAktif->tahun }} - {{ $tahunAktif->semester }})</p>
                    </div>
                </div>
                <button @click="addModal = false" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto w-full">
                <form action="{{ route('rombels.store') }}" method="POST" id="formAddRombel" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Jenis Rombel -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Rombel <span class="text-red-500">*</span></label>
                            <select name="jenis_rombel" required class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-sky-500 focus:ring-3 focus:ring-sky-500/20 transition-all shadow-inner">
                                <option value="Kelas" {{ old('jenis_rombel') == 'Kelas' ? 'selected' : '' }}>Kelas</option>
                                <option value="Pilihan" {{ old('jenis_rombel') == 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                            </select>
                            @error('jenis_rombel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tingkat Pendidikan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tingkat Pendidikan <span class="text-red-500">*</span></label>
                            <select name="tingkat" required class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-sky-500 focus:ring-3 focus:ring-sky-500/20 transition-all shadow-inner">
                                <option value="">Pilih Tingkat</option>
                                <template x-for="t in tingkatOptions" :key="t">
                                    <option :value="t" x-text="'Tingkat ' + t" :selected="t == '{{ old('tingkat') }}'"></option>
                                </template>
                            </select>
                            @error('tingkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Program Keahlian (Show if SMA/SMK) -->
                    <div x-show="jenjang === 'SMA/SMK'" x-cloak>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program / Kompetensi Keahlian</label>
                        <input type="text" name="kompetensi_keahlian" value="{{ old('kompetensi_keahlian') }}" placeholder="Contoh: Rekayasa Perangkat Lunak"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-sky-500 focus:ring-3 focus:ring-sky-500/20 transition-all shadow-inner">
                        <p class="text-xs text-slate-400 mt-1">Hanya diisi untuk tingkat pendidikan SMK/Kejuruan</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Rombel -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Rombel / Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: 1-A"
                                   class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-white focus:border-sky-500 focus:ring-3 focus:ring-sky-500/20 transition-all shadow-sm">
                            @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kurikulum -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kurikulum</label>
                            <select name="kurikulum" class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-50/50 focus:border-sky-500 focus:ring-3 focus:ring-sky-500/20 transition-all shadow-inner">
                                <option value="Kurikulum SD Merdeka" {{ old('kurikulum') == 'Kurikulum SD Merdeka' ? 'selected' : '' }}>Kurikulum SD Merdeka</option>
                            </select>
                        </div>
                    </div>

                    <!-- Wali Kelas -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Wali Kelas (Opsional)</label>
                        <select name="guru_id" class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 bg-slate-100 text-slate-400 focus:border-sky-500 cursor-not-allowed shadow-inner" readonly>
                            <option value="">Belum tersedia (Modul Guru akan datang)</option>
                        </select>
                        <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Fitur pemilihan guru akan aktif setelah Modul Data Guru dirilis.
                        </p>
                    </div>

                </form>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 shrink-0">
                <button type="button" @click="addModal = false" class="px-5 py-2 text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" form="formAddRombel" class="px-6 py-2 text-sm font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-lg shadow-sky-200 transition-all hover:-translate-y-0.5 cursor-pointer">
                    Simpan Rombel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
