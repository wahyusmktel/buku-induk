@extends('layouts.app')

@section('title', 'Anggota Rombel ' . $rombel->nama)
@section('header_title')
    {{ $rombel->nama }}
    @if($rombel->tingkat)
        <span class="ml-2 px-3 py-1 bg-amber-500/20 text-amber-200 text-xs font-black rounded-lg uppercase tracking-wider border border-amber-500/30 shadow-inner">Tingkat {{ $rombel->tingkat }}</span>
    @endif
@endsection
@section('breadcrumb')
    <a href="{{ route('rombels.index') }}" class="hover:text-sky-600">Daftar Rombel</a>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800">{{ $rombel->nama }}</span>
@endsection

@section('content')
<div class="space-y-6" x-data="{ 
    mapModal: false, 
    search: '', 
    students: [], 
    selected: [], 
    isLoading: false,
    timeout: null,
    fetchStudents() {
        this.isLoading = true;
        fetch(`/api/rombels/{{ $rombel->id }}/unassigned-siswas?search=${this.search}`)
            .then(res => res.json())
            .then(data => {
                this.students = data;
                this.isLoading = false;
            });
    },
    debounceSearch() {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => { this.fetchStudents(); }, 300);
    },
    toggleAll(e) {
        if(e.target.checked) {
            this.selected = this.students.map(s => s.id);
        } else {
            this.selected = [];
        }
    },
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
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Anggota Rombongan Belajar</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Total anggota terdaftar: <span class="font-bold text-sky-600">{{ $rombel->siswas->count() }} Siswa</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="mapModal = true; fetchStudents()" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-sky-500/20 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Anggota Kelas
                </button>
                <a href="{{ route('rombels.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-sky-600 px-5 py-2.5 rounded-xl shadow-sm transition-all focus:ring-4 focus:ring-slate-100 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 text-slate-400 uppercase text-[0.7rem] font-black tracking-widest border-b border-slate-100">
                        <th class="px-8 py-4 w-16 text-center">No</th>
                        <th class="px-4 py-4 min-w-[200px]">Nama Lengkap</th>
                        <th class="px-4 py-4 min-w-[150px]">NISN</th>
                        <th class="px-4 py-4 min-w-[150px]">NIK</th>
                        <th class="px-4 py-4 min-w-[100px]">JK</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($rombel->siswas as $index => $siswa)
                    <tr class="hover:bg-sky-50/30 transition-colors group">
                        <td class="px-8 py-4 text-center text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700 group-hover:text-sky-700 transition-colors">{{ $siswa->nama }}</span>
                                    <span class="text-[0.7rem] text-slate-400 font-medium tracking-tight uppercase">Siswa Aktif</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm font-mono text-slate-600">{{ $siswa->nisn ?: '-' }}</td>
                        <td class="px-4 py-4 text-sm font-mono text-slate-600">{{ $siswa->nik ?: '-' }}</td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[0.65rem] font-bold uppercase tracking-wider {{ $siswa->jk == 'L' ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="{{ route('siswas.show', $siswa->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg transition-all border border-sky-100">
                                Profil
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-medium">
                            Tidak ada anggota terdaftar di rombel ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mapping Modal -->
    <div x-show="mapModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div :class="{
                'w-full h-full max-w-none max-h-none rounded-none m-0': isMax,
                'w-full max-w-4xl max-h-[90vh] rounded-3xl': !isMax,
                'transition-all duration-300': !dragging 
             }" 
             :style="(!isMax && posX !== undefined) ? `transform: translate(${posX}px, ${posY}px)` : ''"
             class="bg-white shadow-2xl overflow-hidden border border-white/20 flex flex-col">
            
            <div @mousedown="startDrag($event)" class="bg-sky-600 px-8 py-5 text-white flex items-center justify-between shrink-0 cursor-move select-none">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold tracking-tight">Peta Anggota Rombel: {{ $rombel->nama }}</h3>
                        <p class="text-sky-100 text-xs font-medium">Pilih siswa yang sesuai dan belum memiliki Rombel.</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" @click="isMax = !isMax; if(!isMax) { posX = 0; posY = 0; }" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg x-show="!isMax" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isMax" class="w-4 h-4" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4v4m0-4l-5 5m11-5h4v4m0-4l5 5M4 10V6h4m-4 0l5 5m11 5V6h-4m4 0l-5 5"/></svg>
                    </button>
                    <button type="button" @click="mapModal = false" class="p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 border-b border-slate-100 bg-slate-50/50 shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model="search" @input="debounceSearch()" placeholder="Cari berdasarkan nama, NIS, atau NISN..." 
                           class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border-slate-200 bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 shadow-sm transition-all placeholder:text-slate-400">
                </div>
            </div>

            <form action="{{ route('rombels.assign', $rombel->id) }}" method="POST" id="formAssignSiswas" class="flex flex-col overflow-hidden h-full">
                @csrf
                <div class="overflow-x-auto w-full transition-all" :class="isMax ? 'flex-1 h-full' : 'max-h-[50vh]'">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-slate-100/90 backdrop-blur z-10">
                            <tr class="text-slate-500 uppercase text-[0.7rem] font-black tracking-widest border-b border-slate-200 shadow-sm">
                                <th class="px-6 py-4 w-16 text-center">
                                    <input type="checkbox" @change="toggleAll" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 cursor-pointer w-4 h-4">
                                </th>
                                <th class="px-4 py-4 min-w-[250px]">Informasi Siswa</th>
                                <th class="px-4 py-4 w-32">Tingkat Kelas</th>
                                <th class="px-4 py-4 w-32">Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <tr x-show="isLoading" class="h-48">
                                <td colspan="4" class="px-8 py-0 align-middle">
                                    <div class="flex flex-col items-center justify-center h-full w-full">
                                        <svg class="animate-spin h-8 w-8 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-slate-400 text-sm font-medium mt-4">Memuat data siswa...</p>
                                    </div>
                                </td>
                            </tr>
                            <tr x-show="!isLoading && students.length === 0">
                                <td colspan="4" class="px-8 py-16 text-center text-slate-400 font-medium">
                                    <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    Tidak ada data siswa tanpa rombel yang sesuai tingkat ini.
                                </td>
                            </tr>
                            <template x-for="siswa in students" :key="siswa.id">
                                <tr class="hover:bg-sky-50/50 transition-colors cursor-pointer group">
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="siswa_ids[]" :value="siswa.id" x-model="selected" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 cursor-pointer w-4 h-4 shadow-sm">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-sm shadow-sm" x-text="siswa.nama ? siswa.nama.substring(0,1).toUpperCase() : 'S'">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-700 group-hover:text-sky-700 transition-colors" x-text="siswa.nama"></span>
                                                <span class="text-[0.7rem] text-slate-400 font-medium tracking-tight">
                                                    NISN: <span x-text="siswa.nisn || '-'"></span> <span class="mx-1">&bull;</span> NIS: <span x-text="siswa.nipd || '-'"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-slate-600">
                                        <span x-show="siswa.tingkat_kelas" class="px-2 py-1 bg-amber-50 text-amber-600 rounded-md border border-amber-100 text-xs" x-text="'Tingkat ' + siswa.tingkat_kelas"></span>
                                        <span x-show="!siswa.tingkat_kelas" class="text-slate-400 font-normal italic">-</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[0.65rem] font-bold uppercase tracking-wider" 
                                              :class="siswa.jk == 'L' ? 'bg-sky-100 text-sky-700' : 'bg-rose-100 text-rose-700'"
                                              x-text="siswa.jk == 'L' ? 'Laki-laki' : 'Perempuan'">
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                <div class="text-sm font-bold text-slate-500">
                    <span class="text-sky-600 font-black" x-text="selected.length"></span> siswa dipilih
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="mapModal = false" class="px-5 py-2 text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" form="formAssignSiswas" :disabled="selected.length === 0" class="px-6 py-2 text-sm font-bold text-white bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5 cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Anggota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
