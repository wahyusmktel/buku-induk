@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('header_title', 'Master Mata Pelajaran')
@section('breadcrumb', 'Mata Pelajaran')

@section('content')
<div x-data="{ 
    createModal: false, 
    editModal: false, 
    editData: { id: '', nama: '', kelompok: '', urutan: '', is_aktif: true } 
}">
    <div class="mb-6 flex justify-between items-center px-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Mata Pelajaran</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola daftar mata pelajaran yang digunakan pada formulir nilai rapor / prestasi.</p>
        </div>
        
        <button @click="createModal = true" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-sky-600/20 transition-all hover:shadow-md cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mapel
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @foreach ($errors->all() as $error)
    <div class="mb-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-semibold">{{ $error }}</p>
    </div>
    @endforeach

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-xs font-extrabold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6">Nama Mata Pelajaran</th>
                        <th class="py-4 px-6 w-48">Kelompok / Kategori</th>
                        <th class="py-4 px-6 w-32 text-center">Status</th>
                        <th class="py-4 px-6 w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mapels as $mapel)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6 text-center font-bold text-slate-400">
                            {{ $mapel->urutan }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800 text-base leading-tight">{{ $mapel->nama }}</p>
                        </td>
                        <td class="py-4 px-6 font-medium">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] font-bold bg-slate-100 text-slate-600 uppercase tracking-widest">
                                {{ $mapel->kelompok }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($mapel->is_aktif)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400">
                                Non-aktif
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                    @click="editModal = true; 
                                            editData.id = '{{ $mapel->id }}'; 
                                            editData.nama = '{{ addslashes($mapel->nama) }}'; 
                                            editData.kelompok = '{{ addslashes($mapel->kelompok) }}'; 
                                            editData.urutan = '{{ $mapel->urutan }}'; 
                                            editData.is_aktif = {{ $mapel->is_aktif ? 'true' : 'false' }};
                                            $nextTick(() => { $refs.editForm.action = '{{ url('mata-pelajaran') }}/' + editData.id; })"
                                    class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all cursor-pointer" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                
                                <form action="{{ route('mata-pelajaran.destroy', $mapel->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini? Opsi ini akan menghapus semua riwayat nilai pada mapel ini!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                </div>
                                <p class="font-bold text-lg text-slate-600">Belum Ada Mata Pelajaran</p>
                                <p class="text-sm">Silakan tambahkan setup Mata Pelajaran untuk Buku Induk.</p>
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
         x-transition.opacity 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="createModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg border border-white/20">
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-6 text-white rounded-t-3xl flex items-center justify-between">
                <h3 class="text-xl font-extrabold tracking-tight">Tambah Mata Pelajaran</h3>
                <button @click="createModal = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('mata-pelajaran.store') }}" method="POST" class="p-8">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama" required placeholder="Contoh: Matematika" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all outline-none font-bold text-slate-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Kelompok</label>
                            <input type="text" list="kategoriList" name="kelompok" required placeholder="Muatan Nasional" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-sky-500 font-bold text-slate-700">
                            <datalist id="kategoriList">
                                <option value="Muatan Nasional">
                                <option value="Muatan Kewilayahan">
                                <option value="Muatan Lokal">
                                <option value="Kelompok Umum">
                                <option value="Kelompok A">
                                <option value="Kelompok B">
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Urutan Tampil</label>
                            <input type="number" name="urutan" required min="1" value="{{ count($mapels) + 1 }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-sky-500 font-bold text-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer mt-2 group">
                            <input type="checkbox" name="is_aktif" value="1" checked class="w-5 h-5 text-sky-600 rounded border-slate-300 focus:ring-sky-500 cursor-pointer">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-sky-700">Aktif Digunakan</span>
                        </label>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="createModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-lg transition-all cursor-pointer">Simpan Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" 
         x-transition.opacity 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="editModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg border border-white/20">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-6 text-white rounded-t-3xl flex items-center justify-between">
                <h3 class="text-xl font-extrabold tracking-tight">Edit Mata Pelajaran</h3>
                <button @click="editModal = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" x-ref="editForm" class="p-8">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama" x-model="editData.nama" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 font-bold text-slate-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Kelompok</label>
                            <input type="text" list="kategoriList" name="kelompok" x-model="editData.kelompok" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 font-bold text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Urutan Tampil</label>
                            <input type="number" name="urutan" x-model="editData.urutan" required min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 font-bold text-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer mt-2 group">
                            <input type="checkbox" name="is_aktif" value="1" x-model="editData.is_aktif" class="w-5 h-5 text-amber-500 rounded border-slate-300 focus:ring-amber-500 cursor-pointer">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-amber-700">Aktif Digunakan</span>
                        </label>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-lg transition-all cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
