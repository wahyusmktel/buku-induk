@extends('layouts.app')

@section('title', 'Referensi Ekstrakurikuler')
@section('header_title', 'Referensi Ekstrakurikuler')
@section('breadcrumb')
    <span class="text-slate-500">Data Referensi</span>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800 font-semibold">Referensi Ekstrakurikuler</span>
@endsection

@section('content')
<div class="space-y-6" x-data="{ 
    addModal: {{ $errors->any() && !old('edit_id') ? 'true' : 'false' }}, 
    editModal: {{ old('edit_id') ? 'true' : 'false' }},
    guideModal: false,
    editData: { id: '{{ old('edit_id') }}', nama_ekstrakurikuler: '{{ old('nama_ekstrakurikuler') }}', deskripsi: '{{ old('deskripsi') }}' },
    openEdit(item) {
        this.editData = { ...item };
        this.editModal = true;
    }
}">
    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-medium text-sm flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-rose-50 text-rose-600 p-4 rounded-xl border border-rose-200 font-medium text-sm flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="font-bold">Terjadi Kesalahan:</p>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Referensi Ekstrakurikuler</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola daftar pilihan ekstrakurikuler sekolah.</p>
        </div>
        <div class="flex items-center gap-2">
            @hasanyrole('Super Admin|Operator|Tata Usaha')
            <button @click="addModal = true; editModal = false" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all focus:ring-4 focus:ring-indigo-500/20 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Ekskul
            </button>
            @endhasanyrole
            <button @click="guideModal = true" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan
            </button>
        </div>
    </div>

    @if($ekstrakurikulers->isEmpty())
    <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-sm">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-700">Belum Ada Ekstrakurikuler</h3>
        <p class="text-slate-500 mt-1">Silakan tambah ekstrakurikuler untuk dapat dipilih oleh siswa.</p>
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider">Nama Ekstrakurikuler</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider">Keterangan / Deskripsi</th>
                        @hasanyrole('Super Admin|Operator|Tata Usaha')
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($ekstrakurikulers as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            <h3 class="font-bold text-slate-800 text-sm">{{ $item->nama_ekstrakurikuler }}</h3>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm text-slate-500">{{ $item->deskripsi ?? '—' }}</span>
                        </td>
                        @hasanyrole('Super Admin|Operator|Tata Usaha')
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2" x-data>
                                <button type="button" @click="openEdit({{ Js::from($item) }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('ekstrakurikuler.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus ekstrakurikuler ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endhasanyrole
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- MODAL TAMBAH --}}
    <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="addModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
            <div class="bg-indigo-600 px-6 py-5 text-white flex items-center justify-between">
                <h3 class="text-lg font-bold tracking-tight">Tambah Ekstrakurikuler</h3>
                <button @click="addModal = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('ekstrakurikuler.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ekstrakurikuler" value="{{ old('nama_ekstrakurikuler') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-medium text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" placeholder="Misal: Pramuka">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan Tambahan</label>
                        <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-medium text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" placeholder="Opsional...">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="addModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg transition-colors cursor-pointer">Simpan Ekstrakurikuler</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="editModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
            <div class="bg-indigo-600 px-6 py-5 text-white flex items-center justify-between">
                <h3 class="text-lg font-bold tracking-tight">Edit Ekstrakurikuler</h3>
                <button @click="editModal = false" class="p-2 hover:bg-white/10 rounded-full cursor-pointer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="'{{ url('ekstrakurikuler') }}/' + editData.id" method="POST" class="p-6">
                @csrf @method('PUT')
                <input type="hidden" name="edit_id" :value="editData.id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ekstrakurikuler" x-model="editData.nama_ekstrakurikuler" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-medium text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" placeholder="Misal: Pramuka">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan Tambahan</label>
                        <input type="text" name="deskripsi" x-model="editData.deskripsi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-medium text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" placeholder="Opsional...">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg transition-colors cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PANDUAN --}}
    <div x-show="guideModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="guideModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-6 text-white relative">
                <button @click="guideModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                <h3 class="text-xl font-extrabold tracking-tight">Panduan Ekstrakurikuler</h3>
                <p class="text-indigo-100 text-sm mt-0.5 font-medium">Cara megelola data ekstrakurikuler</p>
            </div>
            <div class="p-8 text-slate-600 space-y-4 text-sm">
                <p><strong>1. Tujuan Referensi:</strong> Halaman ini berfungsi sebagai referensi (Master Data) untuk seluruh ekstrakurikuler yang ada di sekolah. Saat Anda menginput nilai semester, opsi ekstrakurikuler ini akan muncul secara otomatis dan dinamis.</p>
                <p><strong>2. Format Laporan:</strong> Ekstrakurikuler yang diikuti oleh siswa akan dicetak pada rapor dan Buku Induk (kolom per semester) dengan nilai predikat (misal: Sangat Baik).</p>
                <p><strong>3. Edit & Hapus:</strong> Anda bisa mengubah nama atau menambahkan deskripsi. Tidak disarankan menghapus ekskul apabila sudah pernah ada nilai siswa yang berelasi dengannya.</p>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="guideModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-200 transition-all cursor-pointer">Mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection
