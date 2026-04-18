@extends('layouts.app')

@section('title', 'Sampah / Data Terhapus')
@section('header_title', 'Sampah')
@section('breadcrumb')
    <span class="text-slate-500">Kelola</span>
    <span class="text-slate-300 mx-1">/</span>
    <span class="text-slate-800">Sampah / Data Terhapus</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Sampah / Data Terhapus</h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Data siswa yang telah dihapus dan dapat dipulihkan kembali.
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <div class="flex items-center gap-1.5 bg-rose-50 border border-rose-100 rounded-lg px-3 py-1.5 text-xs font-bold text-rose-700">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                {{ $trashed->total() }} Data Terhapus
            </div>
        </div>
    </div>

    {{-- ── Warning Banner ── --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3.5">
        <div class="flex-shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-amber-700">Perhatian: Data yang dihapus bersifat sementara</p>
            <p class="text-xs text-amber-600 mt-0.5">
                Data di halaman ini masih dapat dipulihkan menggunakan tombol <strong>Pulihkan</strong>.
                Gunakan <strong>Hapus Permanen</strong> hanya jika Anda yakin data tidak diperlukan lagi —
                tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
    </div>

    {{-- ── Search ── --}}
    <form method="GET" action="{{ url('/trash') }}" class="flex flex-wrap gap-2 items-center">
        <div class="relative flex-1" style="min-width:180px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q"
                   value="{{ request('q') }}"
                   placeholder="Cari nama siswa atau NISN…"
                   class="w-full pl-9 pr-3 py-2 text-sm border-2 border-slate-200 rounded-xl
                          focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-500/10
                          text-slate-700 font-medium bg-white transition-all">
        </div>

        <button type="submit"
                class="px-4 py-2 text-white text-xs font-bold rounded-xl transition-all
                       whitespace-nowrap cursor-pointer hover:-translate-y-0.5 shrink-0"
                style="background:#be123c;box-shadow:0 4px 10px rgba(190,18,60,.2);"
                onmouseover="this.style.background='#9f1239'"
                onmouseout="this.style.background='#be123c'">
            Cari
        </button>

        @if(request('q'))
        <a href="{{ url('/trash') }}"
           class="px-3 py-2 text-xs font-bold text-slate-400 border-2 border-slate-200 rounded-xl
                  hover:border-rose-300 hover:text-rose-500 hover:bg-rose-50 transition-all whitespace-nowrap shrink-0">
            ✕ Reset
        </a>
        @endif
    </form>

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-5"
         class="fixed z-50 bottom-8 right-8 bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-semibold text-sm">
        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1.5">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    {{-- ── Data Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        @if($trashed->isEmpty())
        <div class="py-20 text-center px-6">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4"
                 style="background:rgba(190,18,60,.08)">
                <svg class="w-9 h-9 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-700">Tidak Ada Data di Sampah</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                @if(request('q'))
                    Tidak ada data yang cocok dengan pencarian "{{ request('q') }}".
                @else
                    Semua data siswa masih aktif. Tidak ada yang dihapus.
                @endif
            </p>
        </div>

        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:linear-gradient(135deg, #9f1239 0%, #e11d48 100%);">
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest"
                            style="color:rgba(255,228,230,.7); width:42px;">#</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">Nama Siswa</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">NISN</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">Kelas</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">Tahun Pelajaran</th>
                        <th class="py-3.5 px-4 text-left text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">Tanggal Dihapus</th>
                        <th class="py-3.5 px-4 text-center text-[0.62rem] font-bold uppercase tracking-widest text-rose-100"
                            style="white-space:nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashed as $index => $siswa)
                    <tr class="border-b border-slate-100 transition-colors"
                        style="border-left:3px solid transparent;"
                        onmouseover="this.style.background='#fff1f2';this.style.borderLeftColor='#e11d48'"
                        onmouseout="this.style.background='';this.style.borderLeftColor='transparent'">

                        <td class="py-3 px-4 text-xs text-slate-400 font-bold">{{ $trashed->firstItem() + $index }}</td>

                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs flex-shrink-0"
                                     style="background:linear-gradient(135deg,#ffe4e6,#fecdd3);color:#e11d48">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-800 text-sm block">{{ $siswa->nama }}</span>
                                    @if($siswa->nipd)
                                    <span class="text-[0.65rem] text-slate-400 font-mono">NIS: {{ $siswa->nipd }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                                {{ $siswa->nisn ?? '—' }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <span class="text-xs font-semibold text-slate-600">
                                {{ $siswa->rombel?->nama ?? ($siswa->rombel_saat_ini ?? '—') }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            @if($siswa->tahunPelajaran)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-lg">
                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $siswa->tahunPelajaran->tahun }} / {{ $siswa->tahunPelajaran->semester }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-100">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $siswa->deleted_at->format('d/m/Y H:i') }}
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2 flex-wrap">

                                {{-- Pulihkan --}}
                                <form action="{{ url('/trash/' . $siswa->id . '/restore') }}" method="POST"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[0.7rem] font-bold rounded-lg
                                                   border transition-all hover:-translate-y-0.5 cursor-pointer"
                                            style="background:#dcfce7;color:#16a34a;border-color:#bbf7d0;"
                                            onmouseover="this.style.background='#16a34a';this.style.color='#fff';this.style.borderColor='#16a34a';this.style.boxShadow='0 4px 12px rgba(22,163,74,.3)'"
                                            onmouseout="this.style.background='#dcfce7';this.style.color='#16a34a';this.style.borderColor='#bbf7d0';this.style.boxShadow=''">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Pulihkan
                                    </button>
                                </form>

                                {{-- Hapus Permanen --}}
                                <form action="{{ url('/trash/' . $siswa->id) }}" method="POST"
                                      class="inline" id="force-delete-form-{{ $siswa->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmForceDelete('{{ $siswa->id }}', '{{ addslashes($siswa->nama) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[0.7rem] font-bold rounded-lg
                                                   border transition-all hover:-translate-y-0.5 cursor-pointer"
                                            style="background:#fff1f2;color:#e11d48;border-color:#fecdd3;"
                                            onmouseover="this.style.background='#e11d48';this.style.color='#fff';this.style.borderColor='#e11d48';this.style.boxShadow='0 4px 12px rgba(225,29,72,.3)'"
                                            onmouseout="this.style.background='#fff1f2';this.style.color='#e11d48';this.style.borderColor='#fecdd3';this.style.boxShadow=''">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus Permanen
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer: row count + pagination --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3
                    px-5 py-3 bg-slate-50/80 border-t border-slate-100">
            <p class="text-xs text-slate-400">
                Menampilkan <strong class="text-slate-600">{{ $trashed->firstItem() }}</strong>–<strong class="text-slate-600">{{ $trashed->lastItem() }}</strong>
                dari <strong class="text-slate-600">{{ $trashed->total() }}</strong> data terhapus
            </p>
            @if($trashed->hasPages())
            <div>
                {{ $trashed->links() }}
            </div>
            @endif
        </div>
        @endif

    </div>{{-- .table card --}}

</div>

<script>
function confirmForceDelete(id, nama) {
    Swal.fire({
        title: 'Hapus Permanen?',
        html: 'Data siswa <strong>' + nama + '</strong> akan dihapus secara permanen dari sistem.<br><span style="font-size:0.75rem;color:#94a3b8">Tindakan ini tidak dapat dibatalkan.</span>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('force-delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
