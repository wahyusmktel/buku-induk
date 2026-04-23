@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('header_title', 'Master Mata Pelajaran')
@section('breadcrumb', 'Mata Pelajaran')

@section('content')
<div x-data="{
    createModal: false,
    editModal: false,
    guideModal: false,
    importModal: false,
    editData: { id: '', nama: '', kelompok: '', urutan: '', is_aktif: true }
}">
    <div class="mb-6 flex justify-between items-center px-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Mata Pelajaran</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola daftar mata pelajaran yang digunakan pada formulir nilai rapor / prestasi.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <button @click="createModal = true" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-sky-600/20 transition-all hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Mapel
            </button>
            <button @click="importModal = true" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm shadow-emerald-600/20 transition-all hover:shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </button>
            <button @click="guideModal = true" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-sky-600 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan
            </button>
        </div>
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

    {{-- ══ FILTER & SEARCH AREA ══ --}}
    <form method="GET" action="{{ route('mata-pelajaran.index') }}" class="mb-6 flex flex-wrap gap-3 items-center px-2">
        {{-- Search input --}}
        <div class="relative flex-1" style="min-width:280px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $search }}"
                   placeholder="Cari nama mata pelajaran atau kelompok…"
                   class="w-full pl-9 pr-3 py-2.5 text-sm border-2 border-slate-100 rounded-xl
                          focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-500/10
                          text-slate-700 font-bold bg-white transition-all">
        </div>

        {{-- Per-page dropdown --}}
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="text-xs text-slate-500 font-bold whitespace-nowrap">Tampilkan</span>
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()"
                        class="appearance-none pl-3 pr-8 py-2.5 text-xs font-black border-2 border-slate-100 rounded-xl
                               bg-white text-slate-600 cursor-pointer transition-all
                               hover:border-sky-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10">
                    @foreach([10, 20, 30, 40, 50, 100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400 pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all cursor-pointer">
            Cari
        </button>

        @if($search || $perPage != 10)
        <a href="{{ route('mata-pelajaran.index') }}"
           class="px-4 py-2.5 text-xs font-bold text-slate-400 border-2 border-slate-100 rounded-xl
                  hover:border-rose-200 hover:text-rose-500 hover:bg-rose-50 transition-all whitespace-nowrap">
            ✕ Reset
        </a>
        @endif
    </form>

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
                    @forelse($mapels as $index => $mapel)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-4 px-6 text-center font-bold text-slate-400">
                            {{ $mapels->firstItem() + $index }}
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
                                    data-id="{{ $mapel->id }}"
                                    data-nama="{{ $mapel->nama }}"
                                    data-kelompok="{{ $mapel->kelompok }}"
                                    data-urutan="{{ $mapel->urutan }}"
                                    data-aktif="{{ $mapel->is_aktif ? '1' : '0' }}"
                                    @click="
                                        editModal = true;
                                        editData.id = $el.dataset.id;
                                        editData.nama = $el.dataset.nama;
                                        editData.kelompok = $el.dataset.kelompok;
                                        editData.urutan = $el.dataset.urutan;
                                        editData.is_aktif = $el.dataset.aktif === '1';
                                        $nextTick(() => { $refs.editForm.action = '{{ url('mata-pelajaran') }}/' + editData.id; })"
                                    class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all cursor-pointer" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                {{-- Toggle Aktif --}}
                                <form id="toggle-form-{{ $mapel->id }}"
                                      action="{{ route('mata-pelajaran.toggle-aktif', $mapel->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                </form>
                                <button type="button"
                                    data-id="{{ $mapel->id }}"
                                    data-nama="{{ $mapel->nama }}"
                                    data-aktif="{{ $mapel->is_aktif ? '1' : '0' }}"
                                    onclick="confirmToggleMapel(this.dataset.id, this.dataset.aktif === '1', this.dataset.nama)"
                                    class="{{ $mapel->is_aktif ? 'text-emerald-500 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-300 hover:text-emerald-600 hover:bg-emerald-50' }} p-1.5 rounded-lg transition-all cursor-pointer"
                                    title="{{ $mapel->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if($mapel->is_aktif)
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                                    @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                                    @endif
                                </button>

                                <form id="delete-form-{{ $mapel->id }}"
                                      action="{{ route('mata-pelajaran.destroy', $mapel->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button"
                                    data-id="{{ $mapel->id }}"
                                    data-nama="{{ $mapel->nama }}"
                                    onclick="confirmDeleteMapel(this.dataset.id, this.dataset.nama)"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
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
                                <p class="font-bold text-lg text-slate-600">Mata Pelajaran Tidak Ditemukan</p>
                                <p class="text-sm">Silakan sesuaikan kata kunci pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: row count + pagination --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3
                    px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            <p class="text-xs text-slate-500 font-medium">
                Menampilkan <strong class="text-slate-700">{{ $mapels->firstItem() ?? 0 }}</strong>–<strong class="text-slate-700">{{ $mapels->lastItem() ?? 0 }}</strong>
                dari <strong class="text-slate-700">{{ $mapels->total() }}</strong> mata pelajaran
            </p>
            @if($mapels->hasPages())
            <div class="paginate-sm">
                {{ $mapels->links() }}
            </div>
            @endif
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

    <!-- Toggle Aktif SweetAlert Script -->
    <script>
    function confirmDeleteMapel(id, nama) {
        Swal.fire({
            title: 'Hapus Mata Pelajaran?',
            html: `
                <div class="text-slate-600 text-sm mt-1">
                    Mata pelajaran <strong class="text-slate-800">${nama}</strong> akan dihapus permanen.
                    <br>
                    <span class="text-rose-600 text-xs mt-2 block font-semibold">
                        ⚠ Seluruh riwayat nilai yang terkait juga akan ikut terhapus dan tidak dapat dipulihkan.
                    </span>
                </div>`,
            icon: 'error',
            iconColor: '#e11d48',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'text-lg font-extrabold text-slate-800',
                confirmButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
                cancelButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
                actions: 'gap-3',
            },
            buttonsStyling: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function confirmToggleMapel(id, isAktif, nama) {
        const deactivating = isAktif;
        Swal.fire({
            title: deactivating ? 'Nonaktifkan Mata Pelajaran?' : 'Aktifkan Mata Pelajaran?',
            html: `
                <div class="text-slate-600 text-sm mt-1">
                    Mata pelajaran <strong class="text-slate-800">${nama}</strong>
                    akan <strong>${deactivating ? 'dinonaktifkan' : 'diaktifkan kembali'}</strong>.
                    ${deactivating ? '<br><span class="text-amber-600 text-xs mt-2 block">Mapel yang dinonaktifkan tidak akan muncul pada formulir input nilai baru.</span>' : ''}
                </div>`,
            icon: deactivating ? 'warning' : 'question',
            iconColor: deactivating ? '#f59e0b' : '#10b981',
            showCancelButton: true,
            confirmButtonColor: deactivating ? '#f59e0b' : '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: deactivating
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg> Ya, Nonaktifkan'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg> Ya, Aktifkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'text-lg font-extrabold text-slate-800',
                confirmButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
                cancelButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
                actions: 'gap-3',
            },
            buttonsStyling: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('toggle-form-' + id).submit();
            }
        });
    }
    </script>

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

    {{-- MODAL IMPORT EXCEL --}}
    <div x-show="importModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="importModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-6 text-white relative">
                <button @click="importModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 class="text-xl font-extrabold tracking-tight">Import Mata Pelajaran</h3>
                <p class="text-emerald-100 text-sm mt-0.5 font-medium">Upload file Excel untuk menambahkan data sekaligus</p>
            </div>

            {{-- Download template --}}
            <div class="mx-6 mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-emerald-800">Gunakan template yang sudah disediakan</p>
                    <p class="text-xs text-emerald-600 mt-0.5">Template berisi contoh data dan catatan pengisian yang benar.</p>
                    <a href="{{ route('mata-pelajaran.template') }}"
                       class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-emerald-700 hover:text-emerald-900 underline underline-offset-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh Template Excel
                    </a>
                </div>
            </div>

            <form action="{{ route('mata-pelajaran.import') }}" method="POST" enctype="multipart/form-data" class="p-6 pt-4">
                @csrf
                <div class="mt-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">File Excel (.xlsx / .xls / .csv)</label>
                    <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-emerald-400 transition-colors cursor-pointer"
                         onclick="document.getElementById('file_excel_mapel').click()">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-semibold text-slate-500" id="mapel_file_label">Klik untuk pilih file atau drag & drop</p>
                        <p class="text-xs text-slate-400 mt-1">Maksimal 4 MB</p>
                        <input type="file" id="file_excel_mapel" name="file_excel" accept=".xlsx,.xls,.csv" class="hidden"
                               onchange="document.getElementById('mapel_file_label').textContent = this.files[0]?.name || 'Klik untuk pilih file'">
                    </div>
                    @error('file_excel') <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700 space-y-1">
                    <p class="font-bold">Ketentuan import:</p>
                    <p>• Baris pertama adalah header (otomatis dikenali).</p>
                    <p>• Kolom wajib: <strong>Nama Mata Pelajaran</strong> dan <strong>Kelompok / Kategori</strong>.</p>
                    <p>• Data duplikat (nama sama) akan dilewati secara otomatis.</p>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="importModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg transition-all cursor-pointer">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PANDUAN --}}
    <div x-show="guideModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="guideModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-6 text-white relative">
                <button @click="guideModal = false" class="absolute top-4 right-4 p-2 hover:bg-white/10 rounded-full transition-colors cursor-pointer text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 class="text-xl font-extrabold tracking-tight">Panduan Mata Pelajaran</h3>
                <p class="text-sky-100 text-sm mt-0.5 font-medium">Cara mengelola data referensi mata pelajaran</p>
            </div>
            <div class="p-8 text-slate-600 space-y-4 text-sm">
                <p><strong>1. Tujuan Referensi:</strong> Halaman ini adalah Master Data mata pelajaran. Saat Anda menginput nilai rapor atau prestasi akademik siswa, daftar mapel ini akan muncul secara otomatis sebagai pilihan.</p>
                <p><strong>2. Kelompok / Kategori:</strong> Isi kolom Kelompok sesuai kurikulum yang berlaku (misal: Muatan Nasional, Muatan Lokal). Pengelompokan ini akan tampil pada laporan rapor dan Buku Induk.</p>
                <p><strong>3. Urutan Tampil:</strong> Angka urutan menentukan posisi mapel pada tabel nilai. Semakin kecil angkanya, semakin atas posisinya.</p>
                <p><strong>4. Aktif / Non-aktif:</strong> Mapel yang dinonaktifkan tidak akan muncul pada formulir input nilai baru, namun riwayat nilai lama tetap tersimpan.</p>
                <p><strong>5. Hapus:</strong> Tidak disarankan menghapus mapel yang sudah memiliki data nilai siswa, karena seluruh riwayat terkait akan ikut terhapus permanen.</p>
            </div>
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="guideModal = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-200 transition-all cursor-pointer">Mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection
