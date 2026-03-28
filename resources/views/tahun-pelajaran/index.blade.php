@extends('layouts.app')

@section('title', 'Tahun Pelajaran')
@section('header_title', 'Tahun Pelajaran')
@section('breadcrumb', 'Tahun Pelajaran')

@section('content')
<div x-data="{ createModal: false }">
    <div class="mb-6 flex justify-between items-center px-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tahun Pelajaran</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola tahun akademik dan semester yang aktif di sistem.</p>
        </div>
        
        <button @click="createModal = true" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-sky-600/20 transition-all hover:shadow-md cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tahun Pelajaran
        </button>
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-xs font-extrabold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Tahun Pelajaran</th>
                        <th class="py-4 px-6">Semester</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tahunPelajarans as $tp)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800 text-base leading-tight">{{ $tp->tahun }}</p>
                        </td>
                        <td class="py-4 px-6 font-medium">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $tp->semester == 'Ganjil' ? 'bg-indigo-50 text-indigo-700' : 'bg-orange-50 text-orange-700' }}">
                                {{ $tp->semester }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($tp->is_aktif)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 uppercase tracking-wider shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif Sesi
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400 uppercase tracking-wider">
                                Non-aktif
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$tp->is_aktif)
                                <form action="{{ route('tahun-pelajaran.activate', $tp->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                        Aktifkan
                                    </button>
                                </form>
                                
                                <form action="{{ route('tahun-pelajaran.destroy', $tp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs font-bold text-slate-300 italic">Sedang Digunakan</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="font-bold text-lg text-slate-600">Belum Ada Tahun Pelajaran</p>
                                <p class="text-sm">Silakan tambah tahun pelajaran baru untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="createModal = false" 
             class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-white/20">
            
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-8 text-white relative">
                <button @click="createModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-extrabold tracking-tight">Tambah Tahun Pelajaran</h3>
                <p class="text-sky-100 text-sm mt-1 font-medium opacity-90">Masukkan detail tahun pelajaran dan semester.</p>
            </div>

            <form action="{{ route('tahun-pelajaran.store') }}" method="POST" class="p-8">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="tahun" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Tahun Pelajaran</label>
                        <input type="text" name="tahun" id="tahun" required placeholder="Contoh: 2026/2027"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all outline-none font-medium">
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Semester</label>
                        <select name="semester" id="semester" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all outline-none font-medium appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%2364748b%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E')">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="createModal = false" class="flex-1 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-[2] py-3 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-sky-200 transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer flex items-center justify-center gap-2">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
